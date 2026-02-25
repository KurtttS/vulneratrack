<?php
class pointsystem extends database {

//Area queries
    public function getAreaAssessment($areaID) {

        $db = $this->getConnection();

        $areaSql = "SELECT Area_Name FROM area WHERE AreaID = ?";
        $areaStmt = $db->prepare($areaSql);
        $areaStmt->execute([$areaID]);
        $area = $areaStmt->fetch();

        $riskSql = "SELECT * FROM vulnerability WHERE Area_ID = ?";
        $riskStmt = $db->prepare($riskSql);
        $riskStmt->execute([$areaID]);
        $risks = $riskStmt->fetchAll();

        return [
            'name' => $area['Area_Name'],
            'risks' => $risks
        ];
    }

    public function deleteVulnerabilityRecord($id) {
        $db = $this->getConnection();
        $sql = "DELETE FROM vulnerability WHERE VulnerabilityID = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function areaAdd($areaID, $name, $points) {
        $db = $this->getConnection();
        $sql = "INSERT INTO vulnerability (Type_of_Vulnerablity, Vulnerability_Points, Area_ID) 
        VALUES (?, ?, ?)";
                
        $stmt = $db->prepare($sql);
        return $stmt->execute([$name, $points, $areaID]);
    }

//Household queries
    public function getHouseholdAssessment($HouseholdId) {
        $db = $this->getConnection();

        $areaIdSql = "SELECT Area_ID FROM household WHERE HouseholdID = ?";
        $areaIdStmt = $db->prepare($areaIdSql);
        $areaIdStmt->execute([$HouseholdId]);
        $areaResult = $areaIdStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$areaResult) return ['error' => 'Household not found'];

        $areaID = $areaResult['Area_ID'];

        $areaSql = "SELECT Area_Name FROM area WHERE AreaID = ?";
        $areaStmt = $db->prepare($areaSql);
        $areaStmt->execute([$areaID]);
        $area = $areaStmt->fetch(PDO::FETCH_ASSOC);

        $riskSql = "SELECT * FROM vulnerability WHERE Area_ID = ?";
        $riskStmt = $db->prepare($riskSql);
        $riskStmt->execute([$areaID]);
        $areaRisks = $riskStmt->fetchAll(PDO::FETCH_ASSOC);

        $houseriskSql = "SELECT * FROM vulnerability WHERE Household_ID = ?";
        $houseriskStmt = $db->prepare($houseriskSql);
        $houseriskStmt->execute([$HouseholdId]);
        $houseRisks = $houseriskStmt->fetchAll(PDO::FETCH_ASSOC);

        $householdSql = "SELECT h.HouseholdID, h.CoordinateX, h.CoordinateY,
        COALESCE(SUM(CASE WHEN u.User_Status = 'PWD' THEN 1 ELSE 0 END), 0) as pwd_count,
        COALESCE(SUM(CASE WHEN u.User_Status = 'Senior Citizen' THEN 1 ELSE 0 END), 0) as elderly_count,
        COALESCE(SUM(CASE WHEN u.User_Status = 'Child' THEN 1 ELSE 0 END), 0) as child_count
        FROM household h 
        LEFT JOIN member m ON h.HouseholdID = m.Household_ID
        LEFT JOIN `user` u ON m.User_ID = u.UserID
        WHERE h.HouseholdID = ? GROUP BY h.HouseholdID";
        
        $hhStmt = $db->prepare($householdSql);
        $hhStmt->execute([$HouseholdId]);
        $householdData = $hhStmt->fetch(PDO::FETCH_ASSOC);

        return [
            'area_name' => $area['Area_Name'],
            'area_risks' => $areaRisks,
            'house_risks' => $houseRisks,
            'household' => $householdData
        ];
    }

    public function householdAdd($householdID, $name, $points) {
        $db = $this->getConnection();
        $sql = "INSERT INTO vulnerability (Type_of_Vulnerablity, Vulnerability_Points, Household_ID) 
        VALUES (?, ?, ?)";
                
        $stmt = $db->prepare($sql);
        return $stmt->execute([$name, $points, $householdID]);
    }

}
?>