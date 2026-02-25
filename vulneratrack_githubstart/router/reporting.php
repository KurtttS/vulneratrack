<?php
if (isset($_POST['submit_report'])) {

    session_start();

    $type = $_POST['problem_type'];
    $description = $_POST['description'];
    $files = $_FILES['report_files'];
    $householdID = $_SESSION['householdid'];
     $report_address = $_SESSION['address'] ?? null;


    include __DIR__ . "/../db/database.php";
    include __DIR__ . "/../classes/reportingclass.php";
    include __DIR__ . "/../processes/reportctrl.php";

    $report = new reportctrl(
        $type,
        $description,
        $files,
        $householdID,
        $report_address
    );

    $report->submitReport();

    header("location: ../user-module/history.php?report=success");
    exit();
}
?>