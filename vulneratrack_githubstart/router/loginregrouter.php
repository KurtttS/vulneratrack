<?php
    session_start();

    //Signup function
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

        $signup = new signupuserctrl($firstname,$lastname,$address,$email,$password,$confirmpass,$dateofbirth,$birthcert,$userstatus,
        $householdmode,$joincode,$longitude,$latitude,$area);
        $userData = $signup->signupuser();

        if ($userData){
            $user = $userData['user'];

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
            $_SESSION["householdid"] = $user['Household_ID'];
            
            $_SESSION["household_members"] = $userData['household_members'];
            $_SESSION["area_risks"]        = $userData['area_risks'];
            $_SESSION["house_risks"]       = $userData['house_risks'];

            $userRepo = new repositoryctrl();
            $result = $userRepo->calculateGrade($userData['area_risks'], $userData['house_risks']);

            $_SESSION["total_points"] = $result['total_points'];
            $_SESSION["risk_grade"]   = $result['grade'];

            }
            
        header("location: ../user-module/home.php?signup=success");
        exit();
    }


    //Login Function
    if (isset($_POST["login"])) {

        $logemail = $_POST["logemail"];
        $logpass  = $_POST["logpass"];

        include __DIR__ . "/../db/database.php";
        include __DIR__ . "/../classes/loginuser.php";
        include __DIR__ . "/../processes/loginuserctrl.php";
        include __DIR__ . "/../classes/userrepository.php";
        include __DIR__ . "/../processes/repositoryctrl.php";

        $loggedUser = new loginuserctrl($logemail, $logpass);
        $userData = $loggedUser->loginuser();

        if ($userData){
            $user = $userData['user'];

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
            $_SESSION["householdid"] = $user['Household_ID'];
            
            $_SESSION["household_members"] = $userData['household_members'];
            $_SESSION["area_risks"]        = $userData['area_risks'];
            $_SESSION["house_risks"]       = $userData['house_risks'];
            
            $userRepo = new repositoryctrl();
            $result = $userRepo->calculateGrade($userData['area_risks'], $userData['house_risks']);

            $_SESSION["total_points"] = $result['total_points'];
            $_SESSION["risk_grade"]   = $result['grade'];
            
            $_SESSION["risk_assessment"] = $riskData;
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

    if (isset($_POST["signout"])) {
        session_start();
        session_unset();
        session_destroy();
        header("location: ../index.php?signout=success");
        exit();
    }

?>