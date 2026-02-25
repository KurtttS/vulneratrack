<?php
    class signupuser extends database {
        protected function setUser($firstname,$lastname,$address,$email,$password,$dateofbirth,$birthcert,$userstatus,
        $householdmode,$joincode,$longitude,$latitude,$area){
            
        $db = $this->getConnection();
                try{
                    $db->beginTransaction();
                    $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
                    $now = $now->format('Y-m-d H:i:s');

                    $stmt = $db->prepare('INSERT INTO  `user` (First_Name, Last_Name, `Address`, Email, Password_Hashed, User_Type, Date_Created, Date_Updated, Date_of_Birth, Birth_Certificate, User_Status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

                    $hashedpass = password_hash($password, PASSWORD_DEFAULT);
                    $stmt->execute(array($firstname,$lastname,$address,$email,$hashedpass,'user',
                    $now,$now,$dateofbirth,$birthcert,$userstatus));

                    $newUserId = $db->lastInsertId(); 

                    $areaID = null;
                    if ($area==='Celine Homes'){
                        $areaID = 1;
                    }
                    if ($area==='East Homes'){
                        $areaID = 2;
                    }
                    if ($area==='Mandalagan, Bacolod City'){
                        $areaID = 3;
                    }
                    if ($householdmode === "create"){
                        $stmthouse = $db->prepare('INSERT INTO household (CoordinateX, CoordinateY, Area_ID)  VALUES (?, ?, ?)');
                        $stmthouse->execute(array($longitude,$latitude,$areaID));
                        $targetHouseholdID = $db->lastInsertID();
                    }
                    else {
                        if (empty($joincode)) {
                            throw new Exception("Join code is required when not creating a new household.");
                        }
                        $targetHouseholdID = $joincode;
                    }

                    $sttmtmember = $db->prepare('INSERT INTO member (User_ID, Household_ID) VALUES (?, ?)');
                    $sttmtmember->execute(array($newUserId,$targetHouseholdID));
                    $db->commit();

                    //Search all members of user in the household 
                    $searchmemSql = "SELECT u.First_Name, u.Last_Name, u.User_Status FROM `user` u INNER JOIN `member` m ON u.UserID = m.User_ID 
                    WHERE m.Household_ID = ?";
                    $searchmemStmt = $db->prepare($searchmemSql);
                    $searchmemStmt->execute([$targetHouseholdID]);
                    $householdMembers = $searchmemStmt->fetchAll(PDO::FETCH_ASSOC);

                    //Getting all vulnerabilities from the area and household
                    $riskSql = "SELECT * FROM vulnerability WHERE Area_ID = ?";
                    $riskStmt = $db->prepare($riskSql);
                    $riskStmt->execute([$areaID]);
                    $areaRisks = $riskStmt->fetchAll(PDO::FETCH_ASSOC);

                    $houseriskSql = "SELECT * FROM vulnerability WHERE Household_ID = ?";
                    $houseriskStmt = $db->prepare($houseriskSql);
                    $houseriskStmt->execute([$targetHouseholdID]);
                    $houseRisks = $houseriskStmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    //gets user details
                    $fetchStmt = $db->prepare('SELECT u.*, m.Household_ID FROM `user` u LEFT JOIN `member` m ON u.UserID = m.User_ID WHERE u.UserID = ?');
                    $fetchStmt->execute([$newUserId]);
                    $userDetails = $fetchStmt->fetch(PDO::FETCH_ASSOC);
                    return [
                        "user" => $userDetails,
                        "household_members" => $householdMembers,
                        "area_risks" => $areaRisks,
                        "house_risks" => $houseRisks
                    ];
                }
                catch (Exception $e) {
                    $db->rollBack();
                    return false;
                }
            }
    }
?>