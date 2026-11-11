<?php
// Set this to your OAuth client ID from Google Cloud Console
// (or define it in your config file and remove this line).
if (!defined('GOOGLE_CLIENT_ID')) {
    define('GOOGLE_CLIENT_ID', 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com');
}

class GoogleAuth {

    public function handleGoogleLogin($id_token) {
        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);
        $response = @file_get_contents($url);

        if ($response === FALSE) {
            return ["error" => "googleautherror"];
        }

        $payload = json_decode($response, true);

        if (!is_array($payload) || !isset($payload['email'])) {
            return ["error" => "invalidgoogletoken"];
        }

        // The token must have been issued for THIS app
        if (($payload['aud'] ?? '') !== GOOGLE_CLIENT_ID) {
            return ["error" => "invalidgoogletoken"];
        }

        // The token must come from Google
        $validIssuers = ['accounts.google.com', 'https://accounts.google.com'];
        if (!in_array($payload['iss'] ?? '', $validIssuers, true)) {
            return ["error" => "invalidgoogletoken"];
        }

        // The email must be verified by Google (prevents account takeover)
        $emailVerified = $payload['email_verified'] ?? false;
        if ($emailVerified !== true && $emailVerified !== 'true') {
            return ["error" => "emailnotverified"];
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

        // NOTE: this assumes checkiftaken() returns TRUE when the email is
        // still available (no account yet), which is how your original code
        // used it. If it actually returns TRUE when the email already exists,
        // change this line to: $isNewUser = !$userRepo->checkiftaken($googleEmail);
        $isNewUser = $userRepo->checkiftaken($googleEmail);

        // Only auto-register when no account exists yet
        if ($isNewUser) {
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

        // 3rd argument 'true' = passwordless Google login
        $loggedUser = new loginuserctrl($googleEmail, null, true);
        $userData = $loggedUser->loginuser();

        if ($userData) {
            return ["status" => "success", "data" => $userData];
        }

        return ["error" => "googleregistrationfailed"];
    }
}