

<header class="homeheader-nav">
    <div class="homeheader-logo" >
        <img src="<?php echo $path_to_root ?? ''; ?>style/imgs/headerlogo.png" alt="Logo">    
    </div>

    <div class="hamburger" id="hamburger">
        <i class="fa-solid fa-bars"></i>
    </div>

    <nav class="homeheader-links" id="navLinks">
        <a href="home.php">Home</a>
        <a href="usermap.php">Map</a>
        <a href="report.php">Report</a>
        <a href="profile.php" class="mobile-only-profile">Profile</a>
        <a href="../index.php" class="mobile-only-profile">Sign Out</a>
    </nav>

    <div class="homeheader-icons">
      <div class="notification-container">
    <a href="javascript:void(0)"><i class="fa-solid fa-bell" id="bellIcon"></i></a>
    <div class="notification-dropdown" id="notificationDropdown">
        <div class="dropdown-header">
            <h1>Notifications</h1>
            <h2>coming soon Feature</h2>
        </div>
        <hr>
        <div class="notification-list">
            <a href="#"><i class="fa-solid fa-circle-info"></i> &nbsp COMING SOON</a>
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

</header>