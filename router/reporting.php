<?php
if (isset($_POST['submit_report'])) {

    require_once __DIR__ . '/../bootstrap.php';
    session_start();

    $type = $_POST['problem_type'];
    $description = $_POST['description'];
    $files = $_FILES['report_files'] ?? ['name' => [], 'error' => [], 'tmp_name' => []];
    $householdID = $_SESSION['householdid'];
     $report_address = $_SESSION['address'] ?? null;
      $located = $_POST['address'] ?? null;


    include __DIR__ . "/../db/database.php";
    include __DIR__ . "/../classes/reportingclass.php";
    include __DIR__ . "/../processes/reportctrl.php";

    $report = new reportctrl(
        $type,
        $description,
        $files,
        $householdID,
        $report_address,
        $located
    );

    try {
        $report->submitReport();
    } catch (RuntimeException $error) {
        error_log('Report submission failed: ' . $error->getMessage());
        header("location: ../user-module/report.php?error=upload");
        exit();
    }

    header("location: ../user-module/history.php?report=success");
    exit();
}
?>