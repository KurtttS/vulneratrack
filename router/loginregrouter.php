<?php
    require_once __DIR__ . '/../bootstrap.php';
    session_start();

    // Signup function
    if (isset($_POST["signup_step0"])) {

        include __DIR__ . "/../db/database.php";
        include __DIR__ . "/../classes/userrepository.php";
        include __DIR__ . "/../processes/repositoryctrl.php";
        // Store data
        $_SESSION["signup"] = [
            "email" => $_POST["email"],
            "password"  => $_POST["password"],
            "confirmpass"     => $_POST["confirmpass"],
            "householdmode" => $_POST["householdmode"],
            "joincode" => $_POST["joincode"]
        ];

        $userRepo = new repositoryctrl();
        if (!$userRepo->checkiftaken($_SESSION['signup']['email'])) {
            header("Location: ../loginreg-module/signup.php?error=emailtaken");
            exit();
        }

        if (!$userRepo->passcheck($_SESSION['signup']["password"], $_SESSION['signup']["confirmpass"])) {
            header("Location: ../loginreg-module/signup.php?error=passwordmismatch");
            exit();
        }

        header("Location: ../loginreg-module/signup-con.php");
        exit();
    }

    if (isset($_POST["signup_step1"]) && isset($_SESSION["signup"])) {

        $firstname = $_POST["firstname"];
        $lastname  = $_POST["lastname"];
        $address = $_POST["address"];
        $email     = $_SESSION["signup"]["email"];
        $password  = $_SESSION["signup"]["password"];
        $confirmpass = $_SESSION["signup"]["confirmpass"];
        $dateofbirth = $_POST["dateofbirth"];
        $birthcert = $_POST["birthcert"];
        $userstatus = $_POST["status"];
        $contactnum = $_POST["contactnum"];
        $householdmode  = $_SESSION["signup"]["householdmode"];
        $joincode  = $_SESSION["signup"]["joincode"];
        $longitude = $_POST["longitude"];
        $latitude = $_POST["latitude"];
        $area = $_POST["areaname"];

        unset($_SESSION["signup"]);

        include __DIR__ . "/../db/database.php";
        include __DIR__ . "/../classes/signupuser.php";
        include __DIR__ . "/../processes/signupuserctrl.php";
        include __DIR__ . "/../classes/userrepository.php";
        include __DIR__ . "/../processes/repositoryctrl.php";

        $signup = new signupuserctrl($firstname,$lastname,$address,$email,$password,$confirmpass,$dateofbirth,$birthcert,$userstatus,$contactnum,
        $householdmode,$joincode,$longitude,$latitude,$area);
        $userData = $signup->signupuser();

        if ($userData){
            session_regenerate_id(true);

            $user = $userData['user'];
            $householdloc = $userData['house_location'];

            $_SESSION["userid"] = $user['UserID'];
            $_SESSION["firstName"] = $user['First_Name'];
            $_SESSION["lastName"] = $user['Last_Name'];
            $_SESSION["address"] = $user['Address'];
            $_SESSION["email"] = $user['Email'];
            $_SESSION["usertype"] = $user['User_Type'];
            $_SESSION["datecreated"] = $user['Date_Created'];
            $_SESSION["dateupdated"] = $user['Date_Updated'];
            $_SESSION["dateofbirth"] = $user['Date_of_Birth'];
            $_SESSION["birthcert"] = $user['Birth_Certificate'];
            $_SESSION["status"] = $user['User_Status'];
            $_SESSION["contactnum"] = $user['Contact_Number'];
            $_SESSION["householdid"] = $user['Household_ID'];
            $_SESSION["household_members"] = $userData['household_members'];
            $_SESSION["area_risks"]         = $userData['area_risks'];
            $_SESSION["house_risks"]        = $userData['house_risks'];
            $_SESSION["area_name"] = $userData['area_name'];
            $userRepo = new repositoryctrl();
            $result = $userRepo->calculateGrade($userData['area_risks'], $userData['house_risks']);
            $_SESSION["total_points"] = $result['total_points'];
            $_SESSION["risk_grade"]   = $result['grade'];
            $_SESSION["latitude"] = $householdloc['CoordinateX'];
            $_SESSION["longitude"]   = $householdloc['CoordinateY'];
        }

        header("location: ../user-module/home.php?signup=success");
        exit();
    }


    // Login Function
    if (isset($_POST["login"])) {

        $logemail = $_POST["logemail"];
        $logpass  = $_POST["logpass"];

        // Never allow a blank password through the normal form
        if ($logpass === '' || $logpass === null) {
            header("location: ../index.php?error=wronglogin");
            exit();
        }

        include __DIR__ . "/../db/database.php";
        include __DIR__ . "/../classes/loginuser.php";
        include __DIR__ . "/../processes/loginuserctrl.php";
        include __DIR__ . "/../classes/userrepository.php";
        include __DIR__ . "/../processes/repositoryctrl.php";

        // Third argument false = normal password login
        $loggedUser = new loginuserctrl($logemail, $logpass, false);
        $userData = $loggedUser->loginuser();

        if ($userData){
            session_regenerate_id(true);

            $user = $userData['user'];
            $householdloc = $userData['house_location'];

            $_SESSION["userid"] = $user['UserID'];
            $_SESSION["firstName"] = $user['First_Name'];
            $_SESSION["lastName"] = $user['Last_Name'];
            $_SESSION["address"] = $user['Address'];
            $_SESSION["email"] = $user['Email'];
            $_SESSION["usertype"] = $user['User_Type'];
            $_SESSION["datecreated"] = $user['Date_Created'];
            $_SESSION["dateupdated"] = $user['Date_Updated'];
            $_SESSION["dateofbirth"] = $user['Date_of_Birth'];
            $_SESSION["birthcert"] = $user['Birth_Certificate'];
            $_SESSION["status"] = $user['User_Status'];
            $_SESSION["contactnum"] = $user['Contact_Number'];
            $_SESSION["householdid"] = $user['Household_ID'];
            $_SESSION["household_members"] = $userData['household_members'];
            $_SESSION["area_risks"] = $userData['area_risks'];
            $_SESSION["house_risks"] = $userData['house_risks'];
            $_SESSION["latitude"] = $householdloc['CoordinateX'];
            $_SESSION["longitude"]  = $householdloc['CoordinateY'];
            $_SESSION["area_name"] = $userData['area_name'];
            $userRepo = new repositoryctrl();
            $result = $userRepo->calculateGrade($userData['area_risks'], $userData['house_risks']);

            $_SESSION["total_points"] = $result['total_points'];
            $_SESSION["risk_grade"]   = $result['grade'];

            if($_SESSION["usertype"]==='user') {
                header("location: ../user-module/home.php?login=success");
                exit();
            }
            else if ($_SESSION["usertype"]==='admin') {
                header("location: ../admin-module/adminhome.php?login=success");
                exit();
            }
        }
        else {
            header("location: ../index.php?error=wronglogin");
            exit();
        }
    }


    // Google Sign-In Handler (email only, no other validation)
    if (isset($_POST["google_token"])) {

        // Ask Google to decode the token so the email comes from a signed source
        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($_POST["google_token"]);
        $response = @file_get_contents($url);
        $payload = $response ? json_decode($response, true) : [];

        // The only check: is there an email?
        if (empty($payload['email'])) {
            header("location: ../index.php?error=googleautherror");
            exit();
        }

        $googleEmail     = $payload['email'];
        $googleFirstName = $payload['given_name'] ?? '';
        $googleLastName  = $payload['family_name'] ?? '';
        $defaultAddress  = "Purok Carvic";

        include_once __DIR__ . "/../db/database.php";
        include_once __DIR__ . "/../classes/loginuser.php";
        include_once __DIR__ . "/../processes/loginuserctrl.php";
        include_once __DIR__ . "/../classes/userrepository.php";
        include_once __DIR__ . "/../processes/repositoryctrl.php";

        $userRepo = new repositoryctrl();
        // checkiftaken returns false if the email already exists in the database
        $emailExists = !$userRepo->checkiftaken($googleEmail);

        // New email -> create the account automatically
        if (!$emailExists) {
            include_once __DIR__ . "/../classes/signupuser.php";
            include_once __DIR__ . "/../processes/signupuserctrl.php";

            $autoSignup = new signupuserctrl(
                $googleFirstName,
                $googleLastName,
                $defaultAddress,
                $googleEmail,
                null,           // no password
                null,           // no confirm password
                "2000-01-01",   // dateOfBirth placeholder
                "",             // birthCert
                "Active",       // userStatus
                "",             // contactNum
                "new",          // householdMode
                "",             // joinCode
                0.0,            // longitude
                0.0,            // latitude
                $defaultAddress // areaName
            );
            $autoSignup->signupuser();
        }

        // Log in without a password (third argument true = Google login)
        $loggedUser = new loginuserctrl($googleEmail, null, true);
        $userData = $loggedUser->loginuser();

        if (!$userData) {
            header("location: ../index.php?error=googleautherror");
            exit();
        }

        session_regenerate_id(true);

        $user = $userData['user'];
        $householdloc = $userData['house_location'] ?? [];

        $_SESSION["userid"] = $user['UserID'];
        $_SESSION["firstName"] = $user['First_Name'];
        $_SESSION["lastName"] = $user['Last_Name'];
        $_SESSION["address"] = !empty($user['Address']) ? $user['Address'] : $defaultAddress;
        $_SESSION["email"] = $user['Email'];
        $_SESSION["usertype"] = $user['User_Type'];
        $_SESSION["datecreated"] = $user['Date_Created'] ?? '';
        $_SESSION["dateupdated"] = $user['Date_Updated'] ?? '';
        $_SESSION["dateofbirth"] = $user['Date_of_Birth'] ?? '';
        $_SESSION["birthcert"] = $user['Birth_Certificate'] ?? '';
        $_SESSION["status"] = $user['User_Status'] ?? '';
        $_SESSION["contactnum"] = $user['Contact_Number'] ?? '';
        $_SESSION["householdid"] = $user['Household_ID'] ?? '';
        $_SESSION["household_members"] = $userData['household_members'] ?? [];
        $_SESSION["area_risks"] = $userData['area_risks'] ?? [];
        $_SESSION["house_risks"] = $userData['house_risks'] ?? [];
        $_SESSION["latitude"] = $householdloc['CoordinateX'] ?? '';
        $_SESSION["longitude"] = $householdloc['CoordinateY'] ?? '';
        $_SESSION["area_name"] = $userData['area_name'] ?? '';

        $result = $userRepo->calculateGrade($_SESSION["area_risks"], $_SESSION["house_risks"]);
        $_SESSION["total_points"] = $result['total_points'] ?? 0;
        $_SESSION["risk_grade"] = $result['grade'] ?? '';

        // Always go to home
        header("location: ../user-module/home.php?login=success");
        exit();
    }

    // Signout Function
    if (isset($_POST["signout"])) {
        session_unset();
        session_destroy();
        header("location: ../index.php?signout=success");
        exit();
    }
?>