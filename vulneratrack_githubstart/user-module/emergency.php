<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="../style/body.css">
     <link rel="stylesheet" href="../style/scrollbar.css">
    <link rel="stylesheet" href="../style/userstyle/report.css"> 
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/userstyle/mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>   

<body>
    <?php 
        $path_to_root = "../";
        include '../nav/homenav.php'; ?>
<div class="emergency-main-content">
    <div class="hotline-board">
        <h1 class="main-title">Emergency Hotline</h1>
        
        <div class="national-hotline">
            <span>Philippine National Emergency Hotline</span>
            <h2>911</h2>
        </div>

        <div class="cards-container">
            <div class="contact-card">
                <h3>RED CROSS</h3>
                <p class="click-text">Click to contact</p>
                <a href="tel:4350324" class="phone-strip">
                    <img src="../style/imgs/REDCROSS.png" alt="Logo"> 
                    435-0324
                </a>
                <div class="address-info">
                    <strong>Address:</strong>
                    10th St, Bacolod City, Philippines
                </div>
            </div>

            <div class="contact-card">
                <h3>AMITY</h3>
                <p class="click-text">Click to contact</p>
                <a href="tel:161" class="phone-strip">
                    <img src="../style/imgs/AMITY.png" alt="Logo">
                    161
                </a>
                <div class="address-info">
                    <strong>Address:</strong>
                    Amity Bldg, Hilado Street, Bacolod City, Philippines, 6100
                </div>
            </div>

            <div class="contact-card">
                <h3>DRRMO</h3>
                <p class="click-text">Click to contact</p>
                <a href="tel:4322879" class="phone-strip">
                    <img src="../style/imgs/drrmo.png" alt="Logo">
                    432-2879
                </a>
                <div class="address-info">
                    <strong>Address:</strong>
                    Sardonyx Street, Purok Sunflower, Bacolod, 6100 Negros Occidental
                </div>
            </div>
        </div>
    </div>
</div>
   
    <?php 
        $path_to_root = "../";
        include '../nav/footer.php'; ?>
</body>

</html>