
<link rel="stylesheet" href="../style/adminmobile.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="sidebar">
    <div class="sidebar-header">
        <i class="fa-solid fa-bars sidebar-hamburger"></i>
        <img src="<?php echo $path_to_root ?? ''; ?>style/imgs/footerlogo.png" alt="Logo" class="sidebar-logo">
    </div>

    <div class="sidebar-label"><span>General</span></div>

    <nav class="nav-list">
        <a href="adminhome.php" class="nav-item active"><i class="fa-solid fa-table-columns"></i> <span>Overview</span></a>
        <a href="adminmap.php" class="nav-item"><i class="fa-solid fa-map"></i> <span>Map</span></a>
        <a href="census.php" class="nav-item"><i class="fa-solid fa-users"></i> <span>Census Data</span></a>
        <a href="stats.php" class="nav-item"><i class="fa-solid fa-chart-line"></i> <span>Statistics</span></a>
        <a href="adminreports.php" class="nav-item"><i class="fa-solid fa-clock-rotate-left"></i> <span>Reports</span></a>
        <a href="assessment.php" class="nav-item"><i class="fa-solid fa-circle-plus"></i> <span>Vulnerabilities</span></a>
        <a href="../index.php" id="mobile-nav-item" class="nav-item"><i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Sign Out</span></a>
    </nav>

<div class="user-section">
    <div class="nav-item notifications">
        <!-- javascript:void(0) = wala may matabo if click -->
        <a href="javascript:void(0)" id="bellIcon">
    <i class="fa-solid fa-bell"></i>
    <span>Notifications</span>
</a>

    <div class="notification-dropdown" id="notificationDropdown">
        <div class="notifdropdown-header">
            <h1>Notifications</h1>
            <h2>be alert!</h2>
        </div>
        <hr>
        <div class="notification-list">
            <a href="#"><i class="fa-solid fa-circle-info"></i> <p> a report has been submitted.</p></a>
        </div>
    </div>
    </div>



    
    <div class="admin-profile-container">
        <div class="admin-profile" id="adminProfileTrigger">
            <div class="admin-info">
                <div class="admin-avatar"><i class="fa-solid fa-user"></i></div>
                <span>Admin</span>
            </div>
            <i class="fa-solid fa-chevron-up arrow" id="adminArrow"></i>
        </div>

        <div class="admin-profile-dropdown" id="adminProfileDropdown">
            <div class="dropdown-header">
                    <h1>Admin</h1>
                
                <p>Identify who are vulnerable.</p>
                <hr>
                <form action="../router/loginregrouter.php" method="post">
                    <button class="signoutbtn" name = "signout">Sign Out</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script src="../js/sidebar.js"></script>