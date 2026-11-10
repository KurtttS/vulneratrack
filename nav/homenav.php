<?php
require_once __DIR__ . '/../bootstrap.php';
ob_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["userid"])) {
    header("Location: ../index.php");
    exit();
}
?>

<header class="homeheader-nav">
    <div class="homeheader-logo desktop-logo">
        <img src="<?php echo $path_to_root ?? ''; ?>style/imgs/headerlogo.png" alt="Logo">    
    </div>

    <div class="mobile-logo">
        <img src="<?php echo $path_to_root ?? ''; ?>style/imgs/vulnemobile.png" alt="Logo">
    </div>

    <div class="hamburger" id="hamburger">
        <i class="fa-solid fa-bars"></i>
    </div>

    <nav class="homeheader-links" id="navLinks">
        <a href="home.php">Home</a>
        <a href="usermap.php">Map</a>
        <a href="report.php">Report</a>
        <a href="faq.php">FAQ</a>
        <a href="profile.php" class="mobile-only-profile">Profile</a>
        <a href="../index.php" class="mobile-only-profile">Sign Out</a>
    </nav>

    <div class="homeheader-icons">
      <div class="notification-container">
    <a href="javascript:void(0)"><i class="fa-solid fa-bell" id="bellIcon"></i></a>
    <div class="notification-dropdown" id="notificationDropdown">
        <div class="dropdown-header">
            <h1>Notifications</h1>
        </div>
        <hr>
        <div class="notification-list">
            <a href="#"><i class="fa-solid fa-circle-info"></i> &nbsp No recent notifications</a>
        </div>
    </div>
</div>
        <div class="profile-container">
            <a><i class="fa-solid fa-circle-user" id="profileIcon"></i></a>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <a href="profile.php">
                    <h1><?php echo $_SESSION["firstName"]." ". $_SESSION["lastName"]?></h1>
                     </a>
                    <h2>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspClick name to view profile</h2>
                    <form action="../router/loginregrouter.php" method="post">
                        <button class="signoutbtn" name = "signout">Sign Out</button>
                    </form>
                 </div>
            </div>
        </div>
    </div>

    <script src="../js/profile.js"></script>
    <script src="../js/security.js"></script>
</header>