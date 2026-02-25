<?php
include __DIR__ . '/../db/database.php';
include __DIR__ . '/../classes/reportingclass.php';
include __DIR__ . '/../processes/adminreportfetchctrl.php';

$reportId = $_GET['id'] ?? 0;

$fetch = new adminreportfetchctrl();
$report = $fetch->fetchSingleReport($reportId);

if (!$report) {
    die('Report not found');
}
?>




<!DOCTYPE html>
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
    <link rel="stylesheet" href="../style/adminstyle/adminreports.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  
</head>

<body>
    <div class="page-container">
        <?php 
        $path_to_root = "../";
        include '../nav/sidebar.php'; ?> <div class="adminpage-container">
            <div class="admin-header">
                <?php 
                $path_to_root = "../";
                include '../nav/adminnav.php'; ?> </div>
<div class="content-wrapper">
    <div class="back-btn-wrapper">

    <a href="adminreports.php" class="back-btn">
        ← Back
    </a>
</div>

  <div class="status-stepper" data-id="<?= $report['ReportID'] ?>">

    <div class="step status-btn" data-status="unverified">
        <span class="step-label">Unverified</span>
        <div class="step-number <?= $report['report_status']=='unverified'?'active':'' ?>">1</div>
    </div>

    <div class="step-line"></div>

    <div class="step status-btn" data-status="ongoing">
        <span class="step-label">Ongoing</span>
        <div class="step-number <?= $report['report_status']=='ongoing'?'active':'' ?>">2</div>
    </div>

    <div class="step-line"></div>

    <div class="step status-btn" data-status="resolved">
        <span class="step-label">Resolved</span>
        <div class="step-number <?= $report['report_status']=='resolved'?'active':'' ?>">3</div>
    </div>

    </div>


    <div class="report-detail-card">
        <div class="reporter-info">
            <div class="report-profile">
                <i class="fas fa-user-circle"></i>
                <div class="user-text">
                    <h2>Household #<?= $report['Household_ID'] ?></h2>
                <p><?= htmlspecialchars($report['report_address']) ?></p>

                </div>
            </div>

            <div class="report-type-header">Concern Report</div>

            <div class="info-group">
                <span class="info-label">Type of Problem</span>
                <div class="info-box"><?= htmlspecialchars($report['Type_of_Problem']) ?></div>
            </div>

            <div class="info-group">
                <span class="info-label">Address</span>
                <div class="info-box"><?= htmlspecialchars($report['report_address']) ?></div>
            </div>
        </div>

        <div class="report-explanation">
            <div class="date-reported">
Date Reported: <?= date("n/j/Y", strtotime($report['Date_Sent'])) ?>
</div>

            <div class="explanation-title">Explain Why you are reporting this</div>
            <div class="explanation-box">
    <?= nl2br(htmlspecialchars($report['Report_Message'])) ?>
</div>
        </div>
    </div> 
    </div>
            
<div id="customAlert" class="custom-alert">
    <span id="customAlertText"></span>
</div>

 <script src="../js/reportbtn.js"></script>
            <?php
            $path_to_root = "../";
            include '../nav/footer.php'; ?> </div>
    </div>
</body>
</html>