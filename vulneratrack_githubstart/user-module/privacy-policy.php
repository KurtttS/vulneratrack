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
    <link rel="stylesheet" href="../style/userstyle/profile.css"> 
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/userstyle/mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>   

<body>
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
                <a href="history.php">Reports</a>
                <a href="privacy-policy.php" class="active">Privacy Policy</a>
            </nav>
        </div>

    </div>

<div class="privacy-title">
    <h1>Privacy Policy</h1>
    <p>Effective Date: February 12, 2026</p>
    <p>This Privacy Policy will help you understand how we collect, use, and share your personal information.</p>
</div>
<div class="privacy-wrapper">


    <!-- LEFT / MAIN SCROLL CONTENT -->
    <div class="privacy-content">
        <h2 id="intro">1. Introduction</h2>
        <p>Vulneratrack is a flood monitoring and rescue web application designed to help communities before, during,
            and after flood emergencies. Flooding caused by typhoons, heavy rainfall, and other natural events can put 
            residents at risk, especially those in vulnerable households. Our goal is to provide timely assistance, 
            prioritize high-risk individuals, and support local authorities and emergency responders in managing flood events efficiently.</p>

<p>Protecting the privacy and security of our users’ data is a top priority. The information collected through Vulneratrack
     is used solely to improve disaster preparedness, ensure rapid rescue operations, and help local governments and responders make 
     informed decisions during emergencies. We are committed to transparency, data protection, and compliance with applicable Philippine laws.</p>

        <h2 id="collect">2. Information We Collect</h2>
        <p>We may collect the following information:</p>
        <ul>
  <li>Name, address, and contact number</li>
  <li>Household details (number of occupants, children, seniors, special needs)</li>
  <li>Location data during emergencies</li>
  <li>Emergency reports and chat messages</li>
</ul>

<p>We gather this information through registration forms, user input during emergencies, integration with official census data, and reports submitted by authorized personnel. This allows Vulneratrack to map vulnerable areas, calculate vulnerability points, and provide accurate, 
    real-time information to responders. All data collected is essential for ensuring timely and effective disaster response.</p>


        <h2 id="use">3. How We Use Your Information</h2>
        <p>Your information is used only for:</p>
               <ul>
  <li>Flood risk assessment</li>
  <li>Identifying vulnerable households</li>
  <li>Prioritizing rescue operations</li>
  <li>Coordinating with emergency responders</li>
  <li>Disaster planning and reporting</li>
                </ul>

                <p>Additionally, the data allows us to generate real-time alerts, track flood-prone areas, and improve the overall 
                    efficiency of rescue operations. Statistical reports and graphical visualizations based on collected data help authorities make 
                    informed decisions for future disaster preparedness, resource allocation, and evacuation planning. By using this information responsibly, 
                    Vulneratrack aims to minimize risks, protect lives, and strengthen community resilience during flood emergencies.
                    We do not use your information for marketing, commercial purposes, or any activity unrelated to emergency response.</p>

        <h2 id="share">4. Data Sharing</h2>
        <p>Vulneratrack shares information only when necessary for disaster response and public safety. Your data may be accessed or shared with:</p>

<ul>
<li>Authorized system administrators</li>
<li>Local Government Units (LGUs)</li>
<li>Official emergency response agencies (e.g., rescue teams, disaster management offices)</li>
<li>Government authorities responsible for disaster preparedness and response</li>
</ul>
<p>Information is shared strictly for emergency coordination, rescue prioritization, evacuation planning, and disaster risk assessment.
We do not sell, rent, trade, or disclose personal information to private companies or third parties for advertising or commercial use.
All agencies and personnel granted access are expected to handle the data responsibly and in accordance with applicable privacy and data protection laws.</p>
        <h2 id="protect">5. Data Protection</h2>
        <p>Vulneratrack takes the protection of your personal and household information very seriously. We implement appropriate technical and organizational measures to safeguard your data from unauthorized access, misuse, loss, or alteration. This includes secure login authentication for administrators and authorized users, role-based access control to limit who can view or modify sensitive information, encrypted data transmission where applicable, and secure database storage. The system is regularly monitored and updated to reduce potential security risks. Access to sensitive household and vulnerability information is strictly limited to authorized personnel involved in disaster management and emergency response. While no system can guarantee absolute security, 
            Vulneratrack continuously works to enhance security measures to ensure that users’ information is protected to the highest standard possible.</p>

        <h2 id="rights">6. Your Rights</h2>
        <p>You may request access to, correction of, or deletion of your personal information, subject to legal requirements.</p>

        <h2 id="legal">7. Legal Compliance</h2>
        <p>Vulneratrack operates in full compliance with the Data Privacy Act of 2012 (RA 10173), including its implementing rules and regulations, as well as other relevant Philippine laws on data protection and disaster management.
We are committed to ensuring that all personal information is handled lawfully, fairly, and transparently. Users’ data is collected and processed only for legitimate purposes related to flood monitoring and emergency response. Any disclosure, storage, or processing of 
information follows strict legal guidelines, and we regularly review our policies and procedures to maintain compliance and uphold users’ privacy rights.</p>

        <h2 id="contact">8. Contact Us</h2>
        <p>For questions or concerns, please contact: Vulneratrack System Administrator [ackkthree@gmail.com / 0929 824 2625]</p>
    </div>

    <!-- RIGHT / FIXED TABLE OF CONTENTS -->
    <div class="privacy-toc">
        <h3>Table of Contents</h3>
        <ul>
            <li><a href="#intro" class="toc-link">1. Introduction</a></li>
            <li><a href="#collect" class="toc-link">2. Information We Collect</a></li>
            <li><a href="#use" class="toc-link">3. How We Use Your Information</a></li>
            <li><a href="#share" class="toc-link">4. Data Sharing</a></li>
            <li><a href="#protect" class="toc-link">5. Data Protection</a></li>
            <li><a href="#rights" class="toc-link">6. Your Rights</a></li>
            <li><a href="#legal" class="toc-link">7. Legal Compliance</a></li>
            <li><a href="#contact" class="toc-link">8. Contact Us</a></li>
        </ul>
    </div>

</div>









  
  
  
  
  
  

  
  <?php 
        $path_to_root = "../";
        include '../nav/footer.php'; ?>
</body>

</html>