<?php
class GoogleAuth {
    
    public function handleGoogleLogin($id_token) {
        // Verify token with Google's endpoint
        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);
        $response = @file_get_contents($url);
        
        if ($response === FALSE) {
            return ["error" => "googleautherror"];
        }

        $payload = json_decode($response, true);

        if (!isset($payload['email'])) {
            return ["error" => "invalidgoogletoken"];
        }

        $googleEmail = $payload['email'];
        $googleFirstName = $payload['given_name'] ?? '';
        $googleLastName = $payload['family_name'] ?? '';
        $defaultAddress = "Purok Carvic";

        include_once __DIR__ . "/../db/database.php";
        include_once __DIR__ . "/../classes/userrepository.php";
        include_once __DIR__ . "/../processes/repositoryctrl.php";
        include_once __DIR__ . "/../classes/signupuser.php";
        include_once __DIR__ . "/../processes/signupuserctrl.php";
        include_once __DIR__ . "/../classes/loginuser.php";
        include_once __DIR__ . "/../processes/loginuserctrl.php";

        $userRepo = new repositoryctrl();

        // 1. Check if user already exists (checkiftaken returns false if email exists in DB)
        $isTaken = !$userRepo->checkiftaken($googleEmail);

        if ($isTaken) {
            // --- Existing User: Log them in ---
            $loggedUser = new loginuserctrl($googleEmail, "");
            $userData = $loggedUser->loginuser();
            
            if ($userData) {
                return ["status" => "success", "data" => $userData];
            }
        } else {
            // --- New User: Auto-Register with passsword123 & Purok Carvic ---
            $plainPassword = "passsword123";
            $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
            
            $userStatus = "Active";
            $contactNum = "";
            $birthCert = "";
            $dateOfBirth = "2000-01-01";
            $householdMode = "new";
            $joinCode = "";
            $longitude = 0.0;
            $latitude = 0.0;
            $areaName = $defaultAddress;

            $autoSignup = new signupuserctrl(
                $googleFirstName,
                $googleLastName,
                $defaultAddress,
                $googleEmail,
                $hashedPassword,
                $hashedPassword,
                $dateOfBirth,
                $birthCert,
                $userStatus,
                $contactNum,
                $householdMode,
                $joinCode,
                $longitude,
                $latitude,
                $areaName
            );

            $newUserData = $autoSignup->signupuser();

            if ($newUserData) {
                // Fetch the newly created user data
                $loggedUser = new loginuserctrl($googleEmail, "");
                $userData = $loggedUser->loginuser();

                if ($userData) {
                    return ["status" => "success", "data" => $userData];
                }
            }
        }

        return ["error" => "googleregistrationfailed"];
    }
}