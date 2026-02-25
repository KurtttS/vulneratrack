<?php
    class loginuser extends database {
        
        protected function loguser($logemail,$logpass){

                $stmt = $this->getConnection()->prepare('SELECT u.*, m.Household_ID FROM `user` u
                LEFT JOIN `member` m ON u.UserID = m.User_ID 
                WHERE u.Email = ? 
                LIMIT 1');

                if(!$stmt->execute(array($logemail))){
                            $stmt = null;
                            exit();
                    }
                
                $check = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($check && password_verify($logpass, $check['Password_Hashed'])) {
                    $db = $this->getConnection();
                    $householdID = $check['Household_ID'];

                    //Get area id
                    $areaID = null;
                    if ($householdID) {
                        $areaStmt = $db->prepare("SELECT Area_ID FROM household WHERE HouseholdID = ?");
                        $areaStmt->execute([$householdID]);
                        $areaRow = $areaStmt->fetch(PDO::FETCH_ASSOC);
                        $areaID = $areaRow['Area_ID'] ?? null;
                    }

                    //Get household Members
                    $householdMembers = [];
                    if ($householdID) {
                        $memSql = "SELECT u.First_Name, u.Last_Name, u.User_Status 
                                   FROM `user` u 
                                   INNER JOIN `member` m ON u.UserID = m.User_ID 
                                   WHERE m.Household_ID = ?";
                        $memStmt = $db->prepare($memSql);
                        $memStmt->execute([$householdID]);
                        $householdMembers = $memStmt->fetchAll(PDO::FETCH_ASSOC);
                    }

                    //Get area risks
                    $areaRisks = [];
                    if ($areaID) {
                        $riskSql = "SELECT * FROM vulnerability WHERE Area_ID = ?";
                        $riskStmt = $db->prepare($riskSql);
                        $riskStmt->execute([$areaID]);
                        $areaRisks = $riskStmt->fetchAll(PDO::FETCH_ASSOC);
                    }

                    //Get household risks
                    $houseRisks = [];
                    if ($householdID) {
                        $hRiskSql = "SELECT * FROM vulnerability WHERE Household_ID = ?";
                        $hRiskStmt = $db->prepare($hRiskSql);
                        $hRiskStmt->execute([$householdID]);
                        $houseRisks = $hRiskStmt->fetchAll(PDO::FETCH_ASSOC);
                    }

                    return [
                        "user" => $check,
                        "household_members" => $householdMembers,
                        "area_risks" => $areaRisks,
                        "house_risks" => $houseRisks
                    ];
                } 
                else {
                    return false; 
                }
            }
    }
?>