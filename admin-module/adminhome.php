<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/adminstyle/adminbody.css">
    <link rel="stylesheet" href="../style/adminstyle/adminmobile.css">
    <link rel="stylesheet" href="../style/adminstyle/sidebar.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body class="adminhome">
            <div class="page-container">
            <?php 
            $path_to_root = "../";
            include '../nav/sidebar.php'; ?>
                <div class="adminpage-container">
                   
                <div class="main-content">
                    <div class="header-section">
                        <div class="overview-btn">NO MORE OVERVIEW</div>
                        <div class="header-line"></div>
                    </div>

                    <div class="dashboard-grid">
                        <div class="column stats-column">
                            <div class="card stat-card">
                                <p>Total Vulnerable Population</p>
                                <h2>785</h2>
                            </div>
                            <div class="card stat-card">
                                <p>Areas Monitored</p>
                                <h2>3</h2>
                            </div>
                            <div class="card stat-card">
                                <p>Reports real time</p>
                                <h2>7</h2>
                            </div>
                        </div>

                        <div class="column chart-column">
                            <div class="card distribution-card">
                                <h3>Vulnerable Population Distribution</h3>
                                <div class="chart-container">
                                    <div class="pie-chart"></div>
                                </div>
                                <div class="chart-legend">
                                    <div class="legend-item"><i class="dot blue"></i> Pregnant Women </div>
                                    <div class="legend-item"><i class="dot dark-blue"></i> Senior Citizens </div>
                                    <div class="legend-item"><i class="dot navy"></i> PWD </div>
                                </div>
                            </div>
                        </div>

                        <div class="column risk-column">
                            <div class="card risk-summary-card">
                                <h3>Risk Level Summary</h3>
                                <div class="risk-list">
                                    <div><i class="dot red"></i> High risk</div>
                                    <div><i class="dot yellow"></i> Medium risk</div>
                                    <div><i class="dot green"></i> Low risk</div>
                                </div>
                            </div>
                            
                            <div class="card high-risk-card">
                                <p>High risk count</p>
                                <h2 class="text-danger">14</h2>
                            </div>
                            <div class="card reports-card">
                                <h3>Recent Reports</h3>
                                <div class="report-entry text-red">Concern Report - Blk 3 lot 9 Villa Angela Phase 1</div>
                                <div class="report-entry text-orange">Emergency Report - Blk 2 lot 1 Villa Angela Phase 1</div>
                                <div class="report-entry text-orange">Emergency Report - Blk 2 lot 1 Villa Angela Phase 1</div>
                            </div>
                        </div> 
                    </div> <div class="map-section">
                        <div class="map-label">Map</div>
                        <div class="map-wrapper">
                            <div id="map" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>
                </div> 
                <?php 
        $path_to_root = "../";
        include '../nav/footer.php'; ?>
</div>
        </div>

    <script src="../js/overviewmap.js"></script>
</body>
</html>