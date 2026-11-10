<?php
require_once '../db/database.php';

class StatsClass extends database {

    public function getTotalPopulation() {
        $query = 'SELECT COUNT(*) as total FROM "user"';
        return $this->conn->query($query)->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getPopulationPerArea() {
        $query = 'SELECT a."Area_Name" as label, COUNT(m."MemberID") as value
                  FROM area a
                  LEFT JOIN household h ON a."AreaID" = h."Area_ID"
                  LEFT JOIN member m ON h."HouseholdID" = m."Household_ID"
                  GROUP BY a."AreaID", a."Area_Name"';
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVulnerabilityPieData() {
        $query = 'SELECT COUNT(*) as count
                  FROM "user"
                  WHERE "User_Status" IN (\'PWD\', \'Senior Citizen\', \'Child\')';
        $vulnTotal = $this->conn->query($query)->fetch(PDO::FETCH_ASSOC)['count'];

        $totalQuery = 'SELECT COUNT(*) as total FROM "user"';
        $total = $this->conn->query($totalQuery)->fetch(PDO::FETCH_ASSOC)['total'];

        $adultCount = max(0, $total - $vulnTotal);

        return [
            ['label' => 'Able-Bodied', 'value' => (int)$adultCount],
            ['label' => 'Vulnerable Populace', 'value' => (int)$vulnTotal]
        ];
    }

    public function getPopulationBreakdown() {
        $query = 'SELECT "User_Status" as label, COUNT(*) as value
                  FROM "user"
                  WHERE "User_Status" IN (\'PWD\', \'Senior Citizen\', \'Child\')
                  GROUP BY "User_Status"';
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVulnerablePopulation() {
        $query = 'SELECT COUNT(*) as total
                  FROM "user"
                  WHERE "User_Status" IN (\'PWD\', \'Senior Citizen\', \'Child\')';
        return $this->conn->query($query)->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getRealTimeReports() {
        $query = "SELECT COUNT(*) as total FROM report WHERE \"report_status\" != 'resolved'";
        return $this->conn->query($query)->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getResReports() {
        $query = "SELECT COUNT(*) as total FROM report WHERE \"report_status\" = 'resolved'";
        return $this->conn->query($query)->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getRawHouseholdRiskData() {
        $db = $this->conn;

        // Fetch households with demographic counts
        $sql = 'SELECT h."HouseholdID", a."Area_Name", h."Area_ID",
                COALESCE(SUM(CASE WHEN u."User_Status" = \'PWD\' THEN 1 ELSE 0 END), 0) as pwd_count,
                COALESCE(SUM(CASE WHEN u."User_Status" = \'Senior Citizen\' THEN 1 ELSE 0 END), 0) as elderly_count,
                COALESCE(SUM(CASE WHEN u."User_Status" = \'Child\' THEN 1 ELSE 0 END), 0) as child_count
                FROM household h
                LEFT JOIN area a ON h."Area_ID" = a."AreaID"
                LEFT JOIN member m ON h."HouseholdID" = m."Household_ID"
                LEFT JOIN "user" u ON m."User_ID" = u."UserID"
                GROUP BY h."HouseholdID", a."Area_Name", h."Area_ID"';
        
        $households = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        // Fetch all vulnerabilities
        $vulnerabilities = $db->query("SELECT * FROM vulnerability")->fetchAll(PDO::FETCH_ASSOC);

        return [
            'households' => $households,
            'vulnerabilities' => $vulnerabilities
        ];
    }
}
?>