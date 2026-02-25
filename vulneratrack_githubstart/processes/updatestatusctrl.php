<?php

require_once __DIR__ . "/../db/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('invalid method');
}

$reportId = $_POST['report_id'] ?? null;
$status   = $_POST['status'] ?? null;

$allowed = ['unverified','ongoing','resolved'];

if (!$reportId || !in_array($status, $allowed)) {
    exit('invalid data');
}

/*
  Small bridge class to access protected getConnection()
*/
$db = new class extends database {
    public function conn() {
        return $this->getConnection();
    }
};

$conn = $db->conn();

if ($status === 'resolved') {

    $stmt = $conn->prepare("
        UPDATE report
        SET report_status = ?, Date_Resolved = CURDATE()
        WHERE ReportID = ?
    ");

    $stmt->execute([$status, $reportId]);

} else {

    $stmt = $conn->prepare("
        UPDATE report
        SET report_status = ?, Date_Resolved = NULL
        WHERE ReportID = ?
    ");

    $stmt->execute([$status, $reportId]);
}

echo "success";
