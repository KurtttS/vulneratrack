<?php 
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
    <link rel="stylesheet" href="../style/adminstyle/stats.css">
    <link rel="stylesheet" href="../style/adminstyle/adminmobile.css">
    <link rel="stylesheet" href="../style/adminstyle/sidebar.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="adminhome">
    <div class="page-container">
        <?php 
        $path_to_root = "../";
        include '../nav/sidebar.php'; 
        ?>

        <div class="adminpage-container">
            <div class="main-content">
                <div class="header-section">
                    <div class="overview-btn">Statistics</div>
                    <div class="header-line"></div>
                </div>

                <div class="dashboard-grid">
                    <div class="stats-sidebar-col">
                        <div class="card mini-stat">
                            <p>Total Population</p>
                            <h2><?php echo $totalPop; ?></h2>
                        </div>
                        <div class="card mini-stat center-text">
                            <p>Total High Risk Households</p>
                            <h2 class="text-red" id="grand-total-risk"></h2>
                        </div> 
                        
                       
                        
                        <div class="card risk-card">
                            <h3>High Risk Households per Area</h3>
                            
                            <div id="risk-stats-container" 
                                class="risk-stats-row" 
                                data-raw-risk='<?php echo json_encode($statsObj->getRawHouseholdRiskData()); ?>'>
                                <p>Calculating risk assessments...</p>
                            </div>
                        </div>
                    </div>

                    <div class="main-charts-col">
                        <div class="card chart-card">
                            <h3>Population per Area</h3>
                            <div class="chart-container">
                                <canvas id="areaBarChart"></canvas>
                            </div>
                        </div>

                        <div class="card chart-card" style="margin-top:20px;">
                            <h3>Total Population Distribution</h3>
                            <div class="chart-container">
                                <canvas id="vulnPieChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="reports-col">
                        <div class="card chart-card">
                            <h3>Vulnerable Population Distribution</h3>
                            <div class="chart-container">
                                <canvas id="popBarChart"></canvas>
                            </div>
                        </div>

                        <div class="card mini-stat">    
                            <p>Total Vulnerable Population</p>
                            <h2><?php echo $vulnPop; ?></h2>
                        </div>
                        <div class="card mini-stat center-text">
                            <p>Total Resolved Reports</p>
                            <h2><?php echo $resovled; ?></h2>
                        </div> 
                        <div class="card mini-stat">
                            <p>Total Ongoing Reports</p>
                            <h2><?php echo $realTime; ?></h2>
                        </div>
                    </div>
                </div>
                 <?php include '../nav/footer.php'; ?>
            </div>
        </div>
         
    </div>
    <script>
        window.chartData = {
            vulnDist: <?php echo json_encode($vulnDist ?? []); ?>,
            popBreakdown: <?php echo json_encode($popBreakdown ?? []); ?>, 
            areaPop: <?php echo json_encode($areaPop ?? []); ?>, 
            totalPop: <?php echo (int)($totalPop ?? 0); ?>,
            vulnPop: <?php echo (int)($vulnPop ?? 0); ?>
        };
    </script>
            
    <script src="../js/chart.js"></script>
</body>
</html> 