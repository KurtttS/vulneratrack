<?php
class pointsystemctrl extends pointsystem {
    
//Area functions
    public function areashow($areaID) {
        $data = $this->getAreaAssessment($areaID);
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function areaToAdd($areaID, $name, $points) {
        $success = $this->areaAdd($areaID, $name, $points);
        
        header('Content-Type: application/json');
        echo json_encode(['status' => $success ? 'success' : 'error']);
        exit;
    }

//Household functions
    public function householdshow($householdID) {
        $data = $this->getHouseholdAssessment($householdID);
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function householdToAdd($householdID, $name, $points) {
        $success = $this->householdAdd($householdID, $name, $points);
        
        header('Content-Type: application/json');
        echo json_encode(['status' => $success ? 'success' : 'error']);
        exit;
    }

    public  function tofetchcontacts($householdID){
        $contactinfo = $this->fetchhouseholdcontacts($householdID);
        $numbers = [];

            foreach ($contactinfo as $row) {
                $num = $row['Contact_Number'];
                if (strpos($num, '0') === 0) {
                    $num = '63' . substr($num, 1);
                }
                $numbers[] = $num;
            }
            return array_unique($numbers);
    }

    public  function tofetchareacontacts($areaID){
        $contactinfo = $this->fetchareacontacts($areaID);
        $numbers = [];

            foreach ($contactinfo as $row) {
                $num = $row['Contact_Number'];
                if (strpos($num, '0') === 0) {
                    $num = '63' . substr($num, 1);
                }
                $numbers[] = $num;
            }
            return array_unique($numbers);
    }

//Shared function
     public function vulnerabilityToDelete($id) {
        $result = $this->deleteVulnerabilityRecord($id);
        
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Vulnerability deleted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete']);
        }
    }
}
?>