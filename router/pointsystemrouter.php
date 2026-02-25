<?php
require_once '../db/database.php'; 
require_once '../classes/pointsystem.php';
require_once '../processes/pointsystemctrl.php';
require_once '../classes/adminmapping.php';

$controller = new pointsystemctrl();
$mapping = new adminmapping();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {

        //Area cases
        case 'getAreaAssessment':
            if (isset($_GET['areaID'])) {
                $controller->areashow($_GET['areaID']);
            }
            break;

        case 'deleteVulnerability':
            if (isset($_GET['id'])) {
                $controller->vulnerabilityToDelete($_GET['id']);
            }
            break;

        case 'addAreaVulnerability':
            if (isset($_GET['areaID']) && isset($_GET['name']) && isset($_GET['points'])) {
                $controller->areaToAdd($_GET['areaID'], $_GET['name'], $_GET['points']);
            }
            break;
            
        //Household cases
        case 'getHouseholdAssessment':
            if (isset($_GET['householdID'])) {
                $controller->householdshow($_GET['householdID']);
            }
            break;

        case 'deleteHouseholdVulnerability':
            if (isset($_GET['vulnerabilitiyID'])) {
                $controller->householdToDelete($_GET['vulnerabilitiyID']);
            }
            break;

        case 'addHouseholdVulnerability':
            if (isset($_GET['householdID']) && isset($_GET['name']) && isset($_GET['points'])) {
                $controller->householdToAdd($_GET['householdID'], $_GET['name'], $_GET['points']);
            }
            break;
        case 'getAllHouseholdsAssessment':
            $result = $mapping->getAllHouseholdsAssessment();
            echo json_encode($result);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
}
?>