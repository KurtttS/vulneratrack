<?php
class adminmapping extends database {

    public function getAllHouseholdsAssessment() {
        $db = $this->getConnection();

        // 1. Get all household basic info and member counts
        $sql = "SELECT h.HouseholdID, h.CoordinateX, h.CoordinateY, a.Area_Name, h.Area_ID,
                COALESCE(SUM(CASE WHEN u.User_Status = 'PWD' THEN 1 ELSE 0 END), 0) as pwd_count,
                COALESCE(SUM(CASE WHEN u.User_Status = 'Senior Citizen' THEN 1 ELSE 0 END), 0) as elderly_count,
                COALESCE(SUM(CASE WHEN u.User_Status = 'Child' THEN 1 ELSE 0 END), 0) as child_count
                FROM household h 
                LEFT JOIN area a ON h.Area_ID = a.AreaID
                LEFT JOIN member m ON h.HouseholdID = m.Household_ID
                LEFT JOIN `user` u ON m.User_ID = u.UserID
                GROUP BY h.HouseholdID";
        
        $stmt = $db->query($sql);
        $households = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Get ALL vulnerabilities to map them in JavaScript
        $vulSql = "SELECT * FROM vulnerability";
        $vulnerabilities = $db->query($vulSql)->fetchAll(PDO::FETCH_ASSOC);

        return [
            'households' => $households,
            'vulnerabilities' => $vulnerabilities
        ];
    }
}

?>