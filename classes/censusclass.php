<?php
require_once '../db/database.php';

class CensusClass extends database {
    
    public function getAllMembers($offset, $limit) {
        $sql = "SELECT u.*, m.Household_ID 
                FROM user u 
                LEFT JOIN member m ON u.UserID = m.User_ID 
                LIMIT :offset, :limit";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createMember($data) {
        $conn = $this->getConnection();
        $conn->beginTransaction();
        try {
            $sqlUser = "INSERT INTO user (First_Name, Last_Name, Address, Email, Password_Hashed, User_Type, Date_Created, Date_Updated, Date_of_Birth, Birth_Certificate, User_Status) 
                        VALUES (?, ?, ?, ?, ?, 'user', NOW(), NOW(), ?, ?, ?)";
            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->execute([
                $data['first_name'], $data['last_name'], $data['address'], 
                $data['email'], password_hash('password123', PASSWORD_DEFAULT), 
                $data['dob'], $data['birthcert'], $data['status']
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
                    if ($data['area'] ==='Mandalagan, Bacolod City'){
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
            $sqlUser = "UPDATE user SET First_Name = ?, Last_Name = ?, Address = ?, Email = ?, 
                        Date_of_Birth = ?, User_Status = ?, Date_Updated = NOW() 
                        WHERE UserID = ?";
            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->execute([
                $data['first_name'], $data['last_name'], $data['address'], 
                $data['email'], $data['dob'], $data['status'], $id
            ]);

            $sqlMember = "UPDATE member SET Household_ID = ? WHERE User_ID = ?";
            $stmtMember = $conn->prepare($sqlMember);
            $stmtMember->execute([$data['household_id'], $id]);

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

        //check if empty
        if ($householdId) {
            $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM member WHERE Household_ID = ?");
            $stmtCheck->execute([$householdId]);
            $remainingMembers = $stmtCheck->fetchColumn();

            //if no memeber remove
            if ($remainingMembers == 0) {
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

    public function getTotalCount() {
        $sql = "SELECT COUNT(*) FROM user";
        return $this->getConnection()->query($sql)->fetchColumn();
    }
}
?>