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

    if (isset($_POST["google_token"])) {
        $token = $_POST["google_token"];
        $clientId = appEnvironment(
            'GOOGLE_CLIENT_ID',
            '712146024593-hg3e7qib2nck5p4br9gm5cl57mosphe1.apps.googleusercontent.com'
        );

        if (!is_string($token) || $token === '' || strlen($token) > 16384 || !function_exists('curl_init')) {
            header("Location: ../index.php?error=googlelogin");
            exit();
        }

        $curl = curl_init('https://oauth2.googleapis.com/tokeninfo?id_token=' . rawurlencode($token));
        if ($curl === false) {
            header("Location: ../index.php?error=googlelogin");
            exit();
        }
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
        ]);
        $tokenResponse = curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $claims = is_string($tokenResponse) && $httpStatus === 200
            ? json_decode($tokenResponse, true)
            : null;
        $emailVerified = is_array($claims)
            && (($claims['email_verified'] ?? false) === true || ($claims['email_verified'] ?? '') === 'true');

        if (
            !is_array($claims)
            || ($claims['aud'] ?? null) !== $clientId
            || !in_array($claims['iss'] ?? null, ['https://accounts.google.com', 'accounts.google.com'], true)
            || !isset($claims['exp'])
            || !is_numeric($claims['exp'])
            || (int)$claims['exp'] <= time()
            || !$emailVerified
            || !isset($claims['email'])
            || !is_string($claims['email'])
            || !filter_var($claims['email'], FILTER_VALIDATE_EMAIL)
        ) {
            header("Location: ../index.php?error=googlelogin");
            exit();
        }

        include __DIR__ . "/../db/database.php";
        include __DIR__ . "/../classes/loginuser.php";
        include __DIR__ . "/../classes/userrepository.php";
        include __DIR__ . "/../processes/repositoryctrl.php";

        $loggedUser = new loginuser();
        $userData = $loggedUser->loguserWithVerifiedEmail($claims['email']);

        if (!$userData) {
            header("Location: ../index.php?error=googlelogin");
            exit();
        }

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
        $_SESSION["latitude"] = $householdloc['CoordinateX'] ?? null;
        $_SESSION["longitude"] = $householdloc['CoordinateY'] ?? null;
        $_SESSION["area_name"] = $userData['area_name'];

        $userRepo = new repositoryctrl();
        $result = $userRepo->calculateGrade($userData['area_risks'], $userData['house_risks']);
        $_SESSION["total_points"] = $result['total_points'];
        $_SESSION["risk_grade"] = $result['grade'];

        if ($_SESSION["usertype"] === 'user') {
            header("Location: ../user-module/home.php?login=success");
            exit();
        }
        if ($_SESSION["usertype"] === 'admin') {
            header("Location: ../admin-module/adminhome.php?login=success");
            exit();
        }

        session_unset();
        header("Location: ../index.php?error=googlelogin");
        exit();
    }


    // Login Function with server-side safety checks (bypasses HTML required attributes)
    if (isset($_POST["login"])) {

        $logemail = trim($_POST["logemail"] ?? '');
        $logpass  = $_POST["logpass"] ?? '';

        // Validate that fields are not empty server-side
        if ($logemail === '' || $logpass === null || $logpass === '') {
            header("location: ../index.php?error=emptyfields");
            exit();
        }

        // Optional format check for email
        if (!filter_var($logemail, FILTER_VALIDATE_EMAIL)) {
            header("location: ../index.php?error=invalidemail");
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

    // Signout Function
    if (isset($_POST["signout"])) {
        session_unset();
        session_destroy();
        header("location: ../index.php?signout=success");
        exit();
    }

?>