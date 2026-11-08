<?php
require_once '../db/database.php'; 
require_once '../classes/overviewdisplay.php';

class pointsystemctrl extends overviewdisplay {
    public function showOverview() {
        $overview = new overviewdisplay();
        $data = $overview->getTotalPop();

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

// Trigger the function
$controller = new pointsystemctrl();
$controller->showOverview();
?>