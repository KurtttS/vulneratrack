<?php

require_once __DIR__ . "/../classes/reportingclass.php";

if (!isset($_POST['report_id'])) {
    echo "missing id";
    exit;
}

$reportId = $_POST['report_id'];

$report = new reportingclass();   
$deleted = $report->deleteReport($reportId);

if ($deleted) {
    echo "success";
} else {
    echo "failed";
}
