<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/adminstyle/adminmap.css"> 
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
        <div class="map-wrapper">
            <div id="map"></div>

            <div class="custom-api-layer">
                <h3>Map</h3>
            </div>

            <div id="risk-summary-template" style="display: none;">

            <div class="risk-summary-container">
                    <div class="summary-card">
                        <h4>Risk Level Summary</h4>
                        <div class="risk-item">
                            <span class="dot red"></span> High Risk <span class="count">20+</span>
                        </div>
                        <div class="risk-item">
                            <span class="dot yellow"></span> Medium Risk <span class="count">11-19</span>
                        </div>
                        <div class="risk-item">
                            <span class="dot green"></span> Low Risk <span class="count">5-10</span>
                        </div>
                    </div>

                    <div class="count-card">
                        <h4>High risk count</h4>
                        <div class="big-number-red">0</div>
                    </div>
                </div>
            </div>
            <div id="area-menu-template" style="display: none;">
            
            <div class="map-menu-container">
                    <div class="menu-header">
                        <div class="header-text">
                            <h4>Total Areas Secured</h4>
                            <div class="header-underline"></div>
                        </div>
                        <span class="area-count-big">3</span>
                        </div>
                    <div id="button-list" class="menu-button-list">
                    </div>
                </div>
            </div>
        </div>
        <?php
        $path_to_root = "../";
        include '../nav/footer.php'; ?>
</div>
</div>
    
    
    <script src="../js/adminmap.js"></script>
    <script src="../js/leaflet-heat.js"></script>

</body>

</html>