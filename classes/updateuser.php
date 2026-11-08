<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

class updateuser extends database {

    protected function setUserUpdate($userid, $firstname, $lastname, $email, $dateofbirth, $status,  $address, $contactnum, $latitude, $longitude, $area, $password = null) {
        $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $now = $now->format('Y-m-d H:i:s');

        if (!empty($password)) {
            $hashedpass = password_hash($password, PASSWORD_DEFAULT);
            $userstmt = $this->getConnection()->prepare(
                "UPDATE `user` SET First_Name=?, Last_Name=?, `Address`=?, Email=?, Date_of_Birth=?, User_Status=?, Contact_Number=?, Password_Hashed=?, Date_Updated=? WHERE UserID=?"
            );
            $userstmt->execute([$firstname, $lastname, $address, $email, $dateofbirth, $status, $contactnum, $hashedpass, $now, $userid]);
        } else {
            $stmt = $this->getConnection()->prepare(
                "UPDATE `user` SET First_Name=?, Last_Name=?, `Address`=?, Email=?, Date_of_Birth=?, User_Status=?, Contact_Number=?,  Date_Updated=? WHERE UserID=?"
            );
            $stmt->execute([$firstname, $lastname, $address, $email, $dateofbirth, $status, $contactnum, $now, $userid]);
        }

        $areaid = ($area === "Celine Homes") ? 1 : (($area === "East Homes") ? 2 : 3);

        $householdstmt = $this->getConnection()->prepare(
            "UPDATE household 
             SET CoordinateX = ?, CoordinateY = ?, Area_ID = ?
             WHERE HouseholdID = (SELECT Household_ID FROM member WHERE User_ID = ? LIMIT 1)"
        );
        $householdstmt->execute([$latitude, $longitude, $areaid, $userid]);
        
        return $now;
    }

    public function getUpdatedHouseholdMembers($userid) {
        $stmt = $this->getConnection()->prepare(
            "SELECT First_Name, Last_Name, User_Status 
             FROM user 
             WHERE UserID IN (
                 SELECT User_ID FROM member WHERE Household_ID = (
                     SELECT Household_ID FROM member WHERE User_ID = ? LIMIT 1
                 )
             )"
        );
        $stmt->execute([$userid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 
?>