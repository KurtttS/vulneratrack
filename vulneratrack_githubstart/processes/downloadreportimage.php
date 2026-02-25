<?php

require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/../processes/adminreportfetchctrl.php';

if (!isset($_GET['id'])) {
    exit('No report id.');
}

$id = (int)$_GET['id'];

$fetch  = new adminreportfetchctrl();
$report = $fetch->fetchSingleReport($id);

if (!$report || empty($report['Report_Image'])) {
    exit('No image found for this report.');
}

$filePath = $report['Report_Image'];



if (!file_exists($filePath)) {
    exit('File does not exist.');
}

$filename = basename($filePath);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;
