<?php
require_once __DIR__ . '/../bootstrap.php';
session_start();

// ---- PhilSMS OTP helpers (signup phone verification) ----
const SIGNUP_OTP_TTL = 300;          // code is valid for 5 minutes
const SIGNUP_OTP_RESEND_WAIT = 60;   // seconds between "Send OTP" presses
const SIGNUP_OTP_MAX_SENDS = 5;      // max codes sent per signup session
const SIGNUP_OTP_MAX_TRIES = 5;      // max wrong guesses per code

// Accepts 09XXXXXXXXX, 639XXXXXXXXX or +639XXXXXXXXX. Returns 09XXXXXXXXX (the format stored in the DB) or null.
function signupNormalizePhone($raw)
{
    $digits = preg_replace('/[\s\-()]/', '', (string) $raw);
    if (preg_match('/^(?:\+?63|0)(9\d{9})$/', $digits, $m)) {
        return '0' . $m[1];
    }
    return null;
}

function signupSendOtpSms($phone, $code)
{
    $apiKey = appEnvironment('PHILSMS_API_KEY');
    if (!$apiKey) {
        error_log('PhilSMS OTP not sent: PHILSMS_API_KEY is missing or empty in .env');
        return false;
    }
    if (!function_exists('curl_init')) {
        error_log('PhilSMS OTP not sent: the PHP curl extension is not enabled (enable extension=curl in php.ini)');
        return false;
    }

    $payload = [
        'recipient' => '63' . substr($phone, 1),
        'sender_id' => 'PhilSMS',
        'type' => 'plain',
        'message' => "Your Vulneratrack verification code is $code. It expires in 5 minutes. Do not share this code with anyone."
    ];

    $ch = curl_init('https://dashboard.philsms.com/api/v3/sms/send');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($response === false) {
        error_log('PhilSMS OTP curl error (' . curl_errno($ch) . '): ' . curl_error($ch));
    }
    curl_close($ch);

    $result = is_string($response) ? json_decode($response, true) : null;
    if (!is_array($result) || ($result['status'] ?? '') !== 'success') {
        error_log('PhilSMS OTP send failed (recipient ' . $payload['recipient'] . ', HTTP ' . $httpCode . '): ' . (is_string($response) ? $response : 'no response'));
        return false;
    }
    return true;
}

// Checks a code against the OTP stored in the session.
// Returns 'ok', 'invalidphone', 'otprequired', 'otpexpired', 'otptoomany' or 'otpwrong'.
// On 'ok' the OTP is flagged as verified (so the live check and the final submit agree).
function signupCheckOtp($phone, $code)
{
    if ($phone === null) {
        return 'invalidphone';
    }
    $otpState = $_SESSION["signup_otp"] ?? null;
    if (!$otpState || $otpState['phone'] !== $phone) {
        return 'otprequired';
    }
    if (time() > $otpState['expires']) {
        unset($_SESSION["signup_otp"]);
        return 'otpexpired';
    }
    if (!empty($otpState['verified'])) {
        return 'ok';
    }
    if ($otpState['tries'] >= SIGNUP_OTP_MAX_TRIES) {
        unset($_SESSION["signup_otp"]);
        return 'otptoomany';
    }
    if (!preg_match('/^\d{6}$/', (string) $code) || !password_verify($code, $otpState['hash'])) {
        $_SESSION["signup_otp"]['tries']++;
        if ($_SESSION["signup_otp"]['tries'] >= SIGNUP_OTP_MAX_TRIES) {
            unset($_SESSION["signup_otp"]);
            return 'otptoomany';
        }
        return 'otpwrong';
    }
    $_SESSION["signup_otp"]['verified'] = true;
    return 'ok';
}

// Send OTP (called by fetch from the signup page)
if (isset($_POST["send_otp"])) {
    header('Content-Type: application/json');

    $phone = signupNormalizePhone($_POST["contactnum"] ?? '');
    if ($phone === null) {
        echo json_encode(['status' => 'error', 'message' => 'Enter a valid PH mobile number (e.g. 09171234567).']);
        exit();
    }

    $otpState = $_SESSION["signup_otp"] ?? [];
    $wait = SIGNUP_OTP_RESEND_WAIT - (time() - ($otpState['sent_at'] ?? 0));
    if ($wait > 0) {
        echo json_encode(['status' => 'error', 'message' => "Please wait $wait seconds before requesting another code.", 'retry_after' => $wait]);
        exit();
    }
    if (($otpState['send_count'] ?? 0) >= SIGNUP_OTP_MAX_SENDS) {
        echo json_encode(['status' => 'error', 'message' => 'Too many OTP requests. Please try again later.']);
        exit();
    }

    $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    if (!signupSendOtpSms($phone, $code)) {
        echo json_encode(['status' => 'error', 'message' => 'Could not send the OTP right now. Please try again.']);
        exit();
    }

    // Only a hash of the code is kept server-side
    $_SESSION["signup_otp"] = [
        'phone' => $phone,
        'hash' => password_hash($code, PASSWORD_DEFAULT),
        'expires' => time() + SIGNUP_OTP_TTL,
        'sent_at' => time(),
        'send_count' => ($otpState['send_count'] ?? 0) + 1,
        'tries' => 0
    ];

    echo json_encode(['status' => 'success', 'message' => 'OTP sent to ' . $phone . '. It expires in 5 minutes.', 'retry_after' => SIGNUP_OTP_RESEND_WAIT]);
    exit();
}

// Live OTP check (called by fetch once the user has typed all 6 digits)
if (isset($_POST["verify_otp"])) {
    header('Content-Type: application/json');

    $phone = signupNormalizePhone($_POST["contactnum"] ?? '');
    $result = signupCheckOtp($phone, trim($_POST["otp"] ?? ''));

    if ($result === 'ok') {
        echo json_encode(['status' => 'success', 'message' => 'Mobile number verified.']);
        exit();
    }

    $messages = [
        'invalidphone' => 'Enter a valid PH mobile number.',
        'otprequired' => 'Tap Send OTP to get a code for this number.',
        'otpexpired' => 'This OTP has expired. Please request a new one.',
        'otptoomany' => 'Too many incorrect attempts. Please request a new OTP.',
        'otpwrong' => 'Incorrect code. Please try again.'
    ];
    echo json_encode([
        'status' => 'error',
        'message' => $messages[$result],
        'reset' => $result !== 'otpwrong'   // the code can no longer be used; user must request a new one
    ]);
    exit();
}

// Signup function
if (isset($_POST["signup_step0"])) {

    include __DIR__ . "/../db/database.php";
    include __DIR__ . "/../classes/userrepository.php";
    include __DIR__ . "/../processes/repositoryctrl.php";
    // Store data
    $_SESSION["signup"] = [
        "email" => $_POST["email"],
        "password" => $_POST["password"],
        "confirmpass" => $_POST["confirmpass"],
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

    // Verify the phone number with the OTP that was texted via PhilSMS
    $phone = signupNormalizePhone($_POST["contactnum"] ?? '');
    $otpResult = signupCheckOtp($phone, trim($_POST["otp"] ?? ''));
    if ($otpResult !== 'ok') {
        header("Location: ../loginreg-module/signup.php?error=" . $otpResult);
        exit();
    }

    // Verified: the code is single-use
    unset($_SESSION["signup_otp"]);
    $_SESSION["signup"]["contactnum"] = $phone;
    $_SESSION["signup"]["phone_verified"] = true;

    header("Location: ../loginreg-module/signup-con.php");
    exit();
}

if (isset($_POST["signup_step1"]) && !empty($_SESSION["signup"]) && empty($_SESSION["signup"]["phone_verified"])) {
    header("Location: ../loginreg-module/signup.php?error=otprequired");
    exit();
}

if (isset($_POST["signup_step1"]) && isset($_SESSION["signup"])) {

    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $address = $_POST["address"];
    $email = $_SESSION["signup"]["email"];
    $password = $_SESSION["signup"]["password"];
    $confirmpass = $_SESSION["signup"]["confirmpass"];
    $dateofbirth = $_POST["dateofbirth"];
    $birthcert = $_POST["birthcert"];
    $userstatus = $_POST["status"];
    $contactnum = $_SESSION["signup"]["contactnum"];
    $householdmode = $_SESSION["signup"]["householdmode"];
    $joincode = $_SESSION["signup"]["joincode"];
    $longitude = $_POST["longitude"];
    $latitude = $_POST["latitude"];
    $area = $_POST["areaname"];

    unset($_SESSION["signup"]);

    include __DIR__ . "/../db/database.php";
    include __DIR__ . "/../classes/signupuser.php";
    include __DIR__ . "/../processes/signupuserctrl.php";
    include __DIR__ . "/../classes/userrepository.php";
    include __DIR__ . "/../processes/repositoryctrl.php";

    $signup = new signupuserctrl(
        $firstname,
        $lastname,
        $address,
        $email,
        $password,
        $confirmpass,
        $dateofbirth,
        $birthcert,
        $userstatus,
        $contactnum,
        $householdmode,
        $joincode,
        $longitude,
        $latitude,
        $area
    );
    $userData = $signup->signupuser();

    if ($userData) {
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
        $_SESSION["area_name"] = $userData['area_name'];
        $userRepo = new repositoryctrl();
        $result = $userRepo->calculateGrade($userData['area_risks'], $userData['house_risks']);
        $_SESSION["total_points"] = $result['total_points'];
        $_SESSION["risk_grade"] = $result['grade'];
        $_SESSION["latitude"] = $householdloc['CoordinateX'];
        $_SESSION["longitude"] = $householdloc['CoordinateY'];
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
        || (int) $claims['exp'] <= time()
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
    $logpass = $_POST["logpass"] ?? '';

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

    if ($userData) {
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
        $_SESSION["longitude"] = $householdloc['CoordinateY'];
        $_SESSION["area_name"] = $userData['area_name'];

        $userRepo = new repositoryctrl();
        $result = $userRepo->calculateGrade($userData['area_risks'], $userData['house_risks']);

        $_SESSION["total_points"] = $result['total_points'];
        $_SESSION["risk_grade"] = $result['grade'];

        if ($_SESSION["usertype"] === 'user') {
            header("location: ../user-module/home.php?login=success");
            exit();
        } else if ($_SESSION["usertype"] === 'admin') {
            header("location: ../admin-module/adminhome.php?login=success");
            exit();
        }
    } else {
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