<?php
class overviewdisplay extends database {

//Total Pop
public function getTotalPop() {
        $db = $this->getConnection();

        //Get Total Pop
        $totalPopSql = 'SELECT "User_Status" as category, COUNT(*) as total FROM "user" WHERE "User_Status" IN (\'Adult\', \'PWD\', \'Senior Citizen\', \'Child\')
        GROUP BY "User_Status"';
        $totalPopstmt = $db->prepare($totalPopSql);
        $totalPopstmt->execute();
        $totalPopdata = $totalPopstmt->fetchAll(PDO::FETCH_ASSOC);
        
        //Get total number of reports
        $totalNumRepSql = "SELECT COUNT(*) as totalrep FROM report";
        $totalNumRepStmt = $db->prepare($totalNumRepSql);
        $totalNumRepStmt->execute();
        $totalNumRepData = $totalNumRepStmt->fetch(PDO::FETCH_ASSOC);

        //Recent Reports
        $recentRepSql = 'SELECT "Type_of_Problem", "report_address" FROM report ORDER BY "ReportID" DESC LIMIT 3';
        $recentRepStmt = $db->prepare($recentRepSql);
        $recentRepStmt->execute();
        $recentReports = $recentRepStmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'household' => $totalPopdata,
        'totalreports' => $totalNumRepData,
        'recent_reports' => $recentReports 
    ];
    }

}
?>