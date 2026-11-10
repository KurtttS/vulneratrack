
<?php
require_once __DIR__ . '/../bootstrap.php';
appStartSession();
appRequireAuthenticatedUser();
include '../processes/statsctrl.php';
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
    <link rel="stylesheet" href="../style/userstyle/home.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/userstyle/mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="home">
    <div class="content" id = "vulstat" >
        <?php 
        $path_to_root = "../";
        include '../nav/homenav.php'; ?>

        <section class="user">
             <div class="user-left">
        <h1>Hello, <?php echo $_SESSION["firstName"]; ?>!</h1>
        
        <p>
               <i class="fa-solid fa-location-dot" id="loc"></i>
        Your Location: 
            <strong><?php echo $_SESSION["address"]; ?></strong>
        </p>

        <p>
              <i class="fa-solid fa-water" id="loc"></i>
        Vulnerability Status: 
            <span id="riskText">
                <?= $_SESSION["risk_grade"] ?>
            </span>
        </p>
    </div>

            <div class="user-right">
                <div class="slider-container">
                    <div class="slider-wrapper" id="slider">
       
                            <div class="user-card">
                                <h2>What is Vulneratrack?</h2>
                                <p> Vulneratrack is a web platform that provides a functional and reliable analysis of risk assessment and emergency responses towards floods. We gather data regarding the populace and the issues of the local area in order
<strong>to monitor and assess the most vulnerable locations for aid-prioritization and help reduce the risk of flooding.</strong>
</p>
                            </div>
                        <div class="user-card">
                            <h2>The 3 SDG</h2>
                            <div class="sdg-container">
                                <img class="sdgimg" src="../style/imgs/sdg3.png" alt="SDG3">
                                <img class="sdgimg" src="../style/imgs/sdg9.png" alt="SDG9">
                                <img class="sdgimg" src="../style/imgs/sdg11.png" alt="SDG11">
                            </div>
                        </div>

                        <div class="user-card">
    <h2>Your data is used for effective rescue efforts</h2>
    <p>
        Your current living situation and status for household members, along with local issues sent through reports help local responders prioritize and assess 
        how to proceed with rescue and prevention efforts.
    </p>
</div>
                    </div>
                    
                    <button class="prev" onclick="moveSlide(-1)"></button>
                    <button class="next" onclick="moveSlide(1)">|</button>
                  <div class="slider-dots" id="sliderDots"></div>
                </div>
            </div>
        </section>

        <section class="concerns">
            
            <h2>Do you have any concerns?</h2>
            <p>We are happy to help you!</p>

            <div class="cards">
                <div class="card emergency">
                    <h3>For Emergency</h3>
                    <p>Quickly contact rescuers and local authorities when there is immediate danger. to help responders locate you faster and provide timely rescue and relief assistance.</p>
                    <a href="#vulcall">
                    <button class="btn-danger">Call for help</button>
                    </a>
                </div>

                <div class="card concern">
                    <h3>For Concerns</h3>
                    <p>To report non-life-threatening issues such as clogged or damaged drainage, standing water, and other issues that may worsen flooding affecting your area.</p>
                    <a href="report.php">
                    <button class="btn-primary">Report here</button>
                    </a>
                </div>
            </div>
        </section>



  <section class="statistics">
    <div class="section-divider"></div> 
                <h2>Main Features</h2>
                <small>our features for your safety</small>
                    <div class="main-container">
                        <div class="mainbox">
                            
                            <h3>Report a problem or concerns</h3>
                            <p>From potholes to sewage issues, just click here, and help is on the way!</p>
                            <a href="report.php">
                            <button class="btnmain">Report here</button></a>
                        </div>

                        <div class="mainbox">
                            
                            <h3>Check the nearest evacuation area</h3>
                            <p>Need to find high ground fast? Click "Locate" to find your nearest evacuation center instantly.</p>
                             <a href="usermap.php">
                            <button class="btnmain">Locate</button></a>
                        </div>

                        <div class="mainbox">
                            
                            <h3>Identify your vulnerability status</h3>
                            <p>We've used a point-based system to calculate your vulnerability score based on gathered data.</p>
                             <a href="#vulstat">
                            <button class="btnmain">Identify</button></a>
                        </div>
                        
                    
                        <div class="mainbox">
                            
                            <h3>Emergency numbers in one page</h3>
                            <p>All the emergency numbers you need, all in one place.</p>
                            <a href="#vulcall">
                            <button class="btnmain">Contact</button></a>
                        </div>
                    </div>
                    <div class="section-divider"></div> 
                    
            </section>

<div class="emergency-main-content" id="vulcall">
    <div class="hotline-board">
        <h1 class="main-title">Emergency Hotline</h1>

        <a href="tel:911" class="ninewanwan" >
        <div class="national-hotline">
            <span>Philippine National Emergency Hotline</span>
            
            <h2>911</h2> 
        </div>
            </a>
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
   
            <section class="statistics">
                   <div class="section-divider"></div> 
                <h2>Safety by the numbers</h2>
                <small>Real-time metrics for real-life safety.</small>
                    <div class="stats-container">
                        <div class="box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <h3 class="counter" data-target="3">0</h3>
                            <p>Areas Monitored</p>
                        </div>

                        <div class="box">
                            <i class="fa-solid fa-flag"></i>
                            <h3 class="counter" data-target="<?php echo $highRiskCount; ?>"></h3>
                            <p>High Risk Households</p>
                        </div>

                        <div class="box">
                            <i class="fa-solid fa-heart"></i>
                            <h3 class="counter" data-target="<?php echo $totalPop; ?>">0</h3>
                            <p>Total Lives Secured</p>
                        </div>
                    </div>
                       <div class="section-divider"></div> 
            </section>

    </div>
    <script src="../js/slideshow.js"></script>
    <script src="../js/risk.js"></script>
        <?php 
        $path_to_root = "../";
        include '../nav/footer.php'; ?>
</body>
</html>
