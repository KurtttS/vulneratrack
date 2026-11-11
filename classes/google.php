<?php
class GoogleAuth {
    
    public function handleGoogleLogin($id_token) {
        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);
        $response = @file_get_contents($url);
        
        if ($response === FALSE) {
            return ["error" => "googleautherror"];
        }

        $payload = json_decode($response, true);

        if (!isset($payload['email'])) {
            return ["error" => "invalidgoogletoken"];
        }

        $googleEmail     = $payload['email'];
        $googleFirstName = $payload['given_name'] ?? '';
        $googleLastName  = $payload['family_name'] ?? '';
        $defaultAddress  = "Purok Carvic";

        include_once __DIR__ . "/../db/database.php";
        include_once __DIR__ . "/../classes/userrepository.php";
        include_once __DIR__ . "/../processes/repositoryctrl.php";
        include_once __DIR__ . "/../classes/signupuser.php";
        include_once __DIR__ . "/../processes/signupuserctrl.php";
        include_once __DIR__ . "/../classes/loginuser.php";
        include_once __DIR__ . "/../processes/loginuserctrl.php";

        $userRepo = new repositoryctrl();
        $isExistingUser = !$userRepo->checkiftaken($googleEmail);

        // Auto-register new users passwordlessly if they don't exist yet
        if (!$isExistingUser) {
            $autoSignup = new signupuserctrl(
                $googleFirstName,
                $googleLastName,
                $defaultAddress,
                $googleEmail,
                null,           // No password needed
                null,           // No confirm password needed
                "2000-01-01", 
                "", 
                "Active", 
                "", 
                "new", 
                "", 
                0.0, 
                0.0, 
                $defaultAddress
            );
            $autoSignup->signupuser();
        }

        // Pass 'true' as the 3rd argument to signify passwordless Google login
        $loggedUser = new loginuserctrl($googleEmail, null, true);
        $userData = $loggedUser->loginuser();
        
        if ($userData) {
            return ["status" => "success", "data" => $userData];
        }

        return ["error" => "googleregistrationfailed"];
    }
}