<?php

require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/../processes/adminreportfetchctrl.php';
require_once __DIR__ . '/../classes/supabasestorage.php';

if (!isset($_GET['id'])) {
    exit('No report id.');
}

$id = (int)$_GET['id'];

$fetch  = new adminreportfetchctrl();
$report = $fetch->fetchSingleReport($id);

if (!$report || empty($report['Report_Image'])) {
    exit('No image found for this report.');
}

$files = explode(',', $report['Report_Image']);
$filePath = trim($files[0] ?? '');
if ($filePath === '') {
    exit('No image found for this report.');
}

$storage = new SupabaseStorage();
header('Location: ' . $storage->createSignedUrl($filePath));
exit;
