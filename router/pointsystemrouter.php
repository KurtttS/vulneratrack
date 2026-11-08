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
        case 'notifyHighRisk':
                if (isset($_GET['householdID'])) {
                $householdID = $_GET['householdID'];
                
                $recipients = $controller->tofetchcontacts($householdID);

                if (empty($recipients)) {
                    echo json_encode(['status' => 'error', 'message' => 'No contacts found for this house']);
                    break;
                }

                $recipientString = implode(',', $recipients);

                $apiKey = "1872|yqvWwnu2f3SS1rGAIXdhWBGmkWpN74bs2Hk0kbaa969cc45a";
                $message = "EMERGENCY ALERT: Your Household: HOUSEHOLD $householdID has been assessed as HIGH RISK. Please stay alert and follow local safety protocols immediately.";

                $payload = [
                    'recipient' => $recipientString,
                    'sender_id' => 'PhilSMS',
                    'message'   => $message
                ];

                $ch = curl_init('https://dashboard.philsms.com/api/v3/sms/send');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $apiKey,
                    'Content-Type: application/json',
					'Accept: application/json' 
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                
                $response = curl_exec($ch);
                curl_close($ch);
                
                echo json_encode(['status' => 'success', 'details' => json_decode($response)]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing householdID']);
            }
            break;
        case 'notifyAreaRisk':
                if (isset($_GET['areaID'])) {
                    $areaID = $_GET['areaID'];
                    $alert =  $_GET['alertname'];

                    $recipients = $controller->tofetchareacontacts($areaID);

                if (empty($recipients)) {
                    echo json_encode(['status' => 'error', 'message' => 'No contacts found for this area']);
                    break;
                }

                $recipientString = implode(',', $recipients);

                $apiKey = "1872|yqvWwnu2f3SS1rGAIXdhWBGmkWpN74bs2Hk0kbaa969cc45a";
                $message = "EMERGENCY ALERT: $alert warning for residents in your area. Please stay alert and follow local safety protocols immediately.";

                $payload = [
                    'recipient' => $recipientString,
                    'sender_id' => 'PhilSMS',
                    'message'   => $message
                ];

                $ch = curl_init('https://dashboard.philsms.com/api/v3/sms/send');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $apiKey,
                    'Content-Type: application/json',
					'Accept: application/json' 
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                
                $response = curl_exec($ch);
                curl_close($ch);
                
                echo json_encode(['status' => 'success', 'details' => json_decode($response)]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing areaID']);
            }
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
}
?>