<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/adminstyle/stats.css">
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
</head>

<body class="adminhome">
    <div class="page-container">
        <?php 
        $path_to_root = "../";
        include '../nav/sidebar.php'; ?>

        <div class="adminpage-container">
          
            <div class="main-content">
                <div class="header-section">
                    <div class="overview-btn">Statistics</div>
                    <div class="header-line"></div>
                </div>

                <div class="dashboard-grid">
                    <div class="top-stats-row">
                        <div class="card stat-card">
                            <p>Total Population</p>
                            <h2>2321</h2>
                        </div>
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

                    <div class="middle-section">
                        <div class="distribution-col">
                            <div class="card chart-card">
                                <h3>Vulnerable Population Distribution</h3>
                                <div class="chart-container-flex">
                                    <div class="pie-placeholder vulnerable-pie"></div>
                                    <div class="chart-legend">
                                        <div class="legend-item"><i class="dot light-blue"></i> Pregnant Women</div>
                                        <div class="legend-item"><i class="dot blue"></i> Senior Citizens</div>
                                        <div class="legend-item"><i class="dot dark-blue"></i> PWD</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card chart-card">
                                <h3>Total Population Distribution</h3>
                                <div class="chart-container-flex">
                                    <div class="pie-placeholder total-pie"></div>
                                    <div class="chart-legend">
                                        <div class="legend-item"><i class="dot green"></i> Able-bodied Populace</div>
                                        <div class="legend-item"><i class="dot yellow"></i> Vulnerable Populace</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="risk-col">
                            <div class="card map-report-card">
                                <h3>Recent Emergency Report</h3>
                                <div class="map-box">
                                    <div id="statmap"></div>
                                    <div class="map-label-overlay">Celine Homes</div>
                                    <p class="map-subtext">Blocked Road - Blk 3 lot 9</p>
                                </div>
                            </div>

                            <div class="card risk-count-card">
                                <h3>High Risk Households Count</h3>
                                <div class="risk-stats-container">
                                    <div class="risk-data">
                                        <span class="count-num">7</span>
                                        <span class="count-label">Celine Homes</span>
                                    </div>
                                    <div class="risk-data">
                                        <span class="count-num">5</span>
                                        <span class="count-label">East Homes</span>
                                    </div>
                                    <div class="risk-data">
                                        <span class="count-num">2</span>
                                        <span class="count-label">Mandalagan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="history-section">
                        <div class="card history-card">
                            <h3>Population of Able-bodied Populace</h3>
                            <div class="bar-chart-placeholder able-bars"></div>
                        </div>
                        <div class="card history-card">
                            <h3>Population of Vulnerable Populace</h3>
                            <div class="bar-chart-placeholder vulnerable-bars"></div>
                        </div>
                    </div>
                </div> </div>

            <?php include '../nav/footer.php'; ?>
        </div>
    </div>
    <script src="../js/stats.js"></script>
</body>
</html>