<?php
require_once '../db/database.php';

class CensusClass extends database {
    
   public function getAllMembers($offset, $limit, $searchTerm = '', $status = '') {
        $sql = "SELECT u.*, m.Household_ID 
                FROM user u 
                LEFT JOIN member m ON u.UserID = m.User_ID 
                WHERE 1=1";
        
        $params = [];

        if (!empty($searchTerm)) {
            $sql .= " AND (u.First_Name LIKE ? OR u.Last_Name LIKE ? OR u.Address LIKE ? OR u.Email LIKE ?)";
            $searchVal = "%$searchTerm%";
            array_push($params, $searchVal, $searchVal, $searchVal, $searchVal);
        }

        if (!empty($status)) {
            $sql .= " AND u.User_Status = ?";
            $params[] = $status;
        }

        $sql .= " LIMIT ?, ?";
        $params[] = (int)$offset;
        $params[] = (int)$limit;

        $stmt = $this->getConnection()->prepare($sql);
        
        foreach ($params as $key => $val) {
            $paramIndex = $key + 1;
            if (is_int($val)) {
                $stmt->bindValue($paramIndex, $val, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($paramIndex, $val, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createMember($data) {
        $conn = $this->getConnection();
        $conn->beginTransaction();
        try {
            $sqlUser = "INSERT INTO user (First_Name, Last_Name, Address, Email, Password_Hashed, User_Type, Date_Created, Date_Updated, Date_of_Birth, Birth_Certificate, User_Status, Contact_Number) 
                        VALUES (?, ?, ?, ?, ?, 'user', NOW(), NOW(), ?, ?, ?, ?)";
            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->execute([
                $data['first_name'], $data['last_name'], $data['address'], 
                $data['email'], password_hash('password123', PASSWORD_DEFAULT), 
                $data['dob'], $data['birthcert'], $data['status'], $data['contact_num']
            ]);

            $targetHouseholdID = 0;
            $userId = $conn->lastInsertId();

            if ($data['houseChoice'] === "create"){
                
                $areaID = null;
                    if ($data['area'] ==='Celine Homes'){
                        $areaID = 1;
                    }
                    if ($data['area'] ==='East Homes'){
                        $areaID = 2;
                    }
                    if ($data['area'] ==='Purok Carvic'){
                        $areaID = 3;
                    }

                $stmthouse = $conn->prepare('INSERT INTO household (CoordinateX, CoordinateY, Area_ID)  VALUES (?, ?, ?)');
                $stmthouse->execute(array($data['lng'],$data['lat'],$areaID));
                $targetHouseholdID = $conn->lastInsertID();
            }

            if ($data['houseChoice'] === "join"){
                $targetHouseholdID = $data['household_id'];
            }

            $sqlMember = "INSERT INTO member (User_ID, Household_ID) VALUES (?, ?)";
            $stmtMember = $conn->prepare($sqlMember);
            $stmtMember->execute([$userId, $targetHouseholdID]);
            
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }

    public function updateMember($id, $data) {
        $conn = $this->getConnection();
        $conn->beginTransaction();
        try {
            $stmtOld = $conn->prepare("SELECT Household_ID FROM member WHERE User_ID = ?");
            $stmtOld->execute([$id]);
            $oldHouseholdId = $stmtOld->fetchColumn();

           $sqlUser = "UPDATE user SET 
                First_Name = ?, Last_Name = ?, Address = ?, Email = ?, 
                Date_of_Birth = ?, Birth_Certificate = ?, User_Status = ?, 
                Contact_Number = ?, Date_Updated = NOW() 
            WHERE UserID = ?";

            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->execute([
                $data['first_name'], $data['last_name'], $data['address'], $data['email'],
                $data['dob'], $data['birthcert'], $data['status'], $data['contact_num'], $id
            ]);
            

            $stmtMember = $conn->prepare("UPDATE member SET Household_ID = ? WHERE User_ID = ?");
            $stmtMember->execute([$data['household_id'], $id]);

            if ($oldHouseholdId && $oldHouseholdId != $data['household_id']) {
                
                $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM member WHERE Household_ID = ?");
                $stmtCheck->execute([$oldHouseholdId]);
                $remaining = $stmtCheck->fetchColumn();

                //if 0 delete
                if ($remaining == 0) {
                    $stmtDelRep = $conn->prepare("DELETE FROM report WHERE Household_ID = ?");
                    $stmtDelRep->execute([$oldHouseholdId]);

                    $stmtDelVul = $conn->prepare("DELETE FROM vulnerability WHERE Household_ID = ?");
                    $stmtDelVul->execute([$oldHouseholdId]);

                    $stmtDelHouse = $conn->prepare("DELETE FROM household WHERE HouseholdID = ?");
                    $stmtDelHouse->execute([$oldHouseholdId]);
                }
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }

    public function deleteMember($id) {
    $conn = $this->getConnection();
    $conn->beginTransaction();
    try {
        $stmtGetHouse = $conn->prepare("SELECT Household_ID FROM member WHERE User_ID = ?");
        $stmtGetHouse->execute([$id]);
        $householdId = $stmtGetHouse->fetchColumn();

        $stmtMember = $conn->prepare("DELETE FROM member WHERE User_ID = ?");
        $stmtMember->execute([$id]);
    
        $stmtUser = $conn->prepare("DELETE FROM user WHERE UserID = ?");
        $stmtUser->execute([$id]);

    if ($householdId) {
        
        // check if there are people in the household
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM member WHERE Household_ID = ?");
        $stmtCheck->execute([$householdId]);
        $remainingMembers = $stmtCheck->fetchColumn();

        // if no members are left clean up the empty household
        if ($remainingMembers == 0) {
                        
            // remove all reports linked sa empty household
            $stmtDelReports = $conn->prepare("DELETE FROM report WHERE Household_ID = ?");
            $stmtDelReports->execute([$householdId]);

            // remove all vulnerability records connected to empty household
            $stmtDelVuln = $conn->prepare("DELETE FROM vulnerability WHERE Household_ID = ?");
            $stmtDelVuln->execute([$householdId]);

            // safely delete the household itself
            $stmtDelHouse = $conn->prepare("DELETE FROM household WHERE HouseholdID = ?");
            $stmtDelHouse->execute([$householdId]);
        }
    }

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        return false;
    }
}

    public function getTotalCount($searchTerm = '', $status = '') {
        $sql = "SELECT COUNT(*) FROM user u WHERE 1=1";
        $params = [];

        if (!empty($searchTerm)) {
            $sql .= " AND (u.First_Name LIKE ? OR u.Last_Name LIKE ? OR u.Address LIKE ? OR u.Email LIKE ?)";
            $searchVal = "%$searchTerm%";
            array_push($params, $searchVal, $searchVal, $searchVal, $searchVal);
        }

        if (!empty($status)) {
            $sql .= " AND u.User_Status = ?";
            $params[] = $status;
        }

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
?>

