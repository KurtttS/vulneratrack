<?php
session_start();

include __DIR__ . "/../db/database.php";
include __DIR__ . "/../classes/reportingclass.php";
include __DIR__ . "/../processes/reportfetchctrl.php";

$fetch = new reportfetchctrl($_SESSION['householdid'] ?? 0);
$reports = $fetch->fetchReports();
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/scrollbar.css">
    <link rel="stylesheet" href="../style/userstyle/profile.css"> 
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/userstyle/mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>   

<body class="home">
    <?php 
        $path_to_root = "../";
        include '../nav/homenav.php'; ?>

<div class="whole-container">
    <div class="blue-bg">
        <div class="white-profile">
            <div class="left-prof">
                <i class="fa-solid fa-circle-user" id="profileIcon-edit"></i>
                <div class="name-status-container">
 <div class="profile-name"><?php echo $_SESSION["firstName"]." ".$_SESSION["lastName"]?></div>
                    <div class="status">
                        Your Location: <?php echo $_SESSION["address"]; ?> <br>
                        Vulnerability Status: <span class="high-risk"><?= $_SESSION["risk_grade"] ?></span>
                        <br>
                         Household code: <span class="code"><?php echo $_SESSION["householdid"]; ?></span>
                    </div>
                </div>
            </div>
            <button class="btn-out" onclick="window.location.href='../index.php';">Sign out</button> 
        </div>

        <div class="switch-tabs">
            <nav class="profileheader-links">
                <a href="profile.php">Profile</a>
                <a href="history.php" class="active">Reports</a>
                <a href="privacy-policy.php">Privacy Policy</a>
            </nav>
        </div>
    </div>


<div class="history-container">

    <h2 class="history-title">History Reports</h2>

   <?php include __DIR__ . '/../user-module/reporthistory.php'; ?>


</div>


   </div> 


<div class="bg-spacer"></div>

  <script src="../js/profile.js"></script>

    <?php 
        $path_to_root = "../";
        include '../nav/footer.php'; ?>
</body>

</html>