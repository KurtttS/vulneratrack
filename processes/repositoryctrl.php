<?php
class repositoryctrl extends userrepository {

    public  function passcheck($password, $confirmpass) {
            return $password === $confirmpass;
        }

    public function checkiftaken($email) {
            return $this->checkUser($email);
        }
        
    public function calculateGrade($areaRisks, $houseRisks) {
            $total = 0;

            foreach ($areaRisks as $risk) {
                $total += (int)($risk['Vulnerability_Points'] ?? 0);
            }
            foreach ($houseRisks as $risk) {
                $total += (int)($risk['Vulnerability_Points'] ?? 0);
            }

            if ($total >= 20) {
                $grade = "High Risk";
            } elseif ($total >= 10) {
                $grade = "Medium Risk";
            } elseif ($total >= 1) {
                $grade = "Low Risk";
            } else {
                $grade = "No Risk";
            }
    
            return [
                "total_points" => $total,
                "grade" => $grade
            ];
    }
}
?>