<!DOCTYPE html>
<?php 
 include __DIR__ ."/../router/pointsystemrouter.php";
 ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/adminstyle/assessment.css">
    <link rel="stylesheet" href="../style/adminstyle/adminmobile.css">
    <link rel="stylesheet" href="../style/adminstyle/sidebar.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
</head>
<body>
    <div class="page-container">
        <?php 
        $path_to_root = "../";
        include '../nav/sidebar.php'; ?>
        <div class="adminpage-container">
<div class="assessment-container">
    <div class="dashboard-header">
        <div class="title-badge">Point System</div>
        <div class="header-line"></div>
    </div>

    <div class="assessment-section">
        <h2 class="section-title">Area Assessment</h2>
            <button class="btn-next">
                Next
            </button>
        <div class="risk-card">
            <div class="map-placeholder">
                <div id="areamap"></div>
            </div>

            <div class="assessment-data">
                <h3></h3>
                <ul class="risk-list">
                </ul>
                <div class="risk-footer">
                    <span class="risk-label">Risk</span>
                    <span class="risk-score">0</span>
                </div>
            </div>

            <div class="tag-container">
                <div class="tag-grid">
                </div>
            </div>
        </div>
    </div>

    <div class="assessment-section">
        <h2 class="section-title">Household Assessment</h2>
        <div class="search-box">
                    <button onclick="searchByHousehold()"><i class="fas fa-search"></i></button>
                    <input type="text" id="householdSearch" placeholder="Search...">
                </div>
        <div class="risk-card">
            <div class="map-placeholder">
                <div id="housemap"></div>
            </div>

            <div class="houseassessment-data">
                <h3></h3>
                <ul class="household-list">
                </ul>
                <div class="household-footer">
                    <span class="household-label">Risk</span>
                    <span class="household-score">0</span>
                </div>
            </div>

            <div class="housetag-container">
                <div class="housetag-grid">
                    <button class = "housetag">Power Outage<span>+</span></button>
                    <button class = "housetag">Blocked Road<span>+</span></button>
                    <button class = "housetag">Clogged Canals<span>+</span></button>
                    <button class = "housetag">Standing Water<span>+</span></button>
                    <button class = "housetag">Structural Damage<span>+</span></button>
                    <button class = "housetag">Severe Structural Damage<span>+</span></button>
                    <button class = "housetag">Single entry/exit<span>+</span></button>
                </div>
            </div>
        </div>
</div>
</div>
<?php
$path_to_root = "../";
        include '../nav/footer.php'; ?>
</div>
</div>
    <script src="../js/assessment.js"></script>

</body>
</html>