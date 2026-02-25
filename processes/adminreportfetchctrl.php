<?php

require_once __DIR__ . '/../classes/reportingclass.php';

class adminreportfetchctrl extends reportingclass {

    public function __construct() {
        parent::__construct();
    }

    public function fetchAllReports() {

        $stmt = $this->getConnection()->prepare(
            "SELECT *
             FROM report
             ORDER BY Date_Sent DESC"
        );

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchSingleReport($id)
    {
        $stmt = $this->getConnection()->prepare(
            "SELECT *
             FROM report
             WHERE ReportID = ?"
        );

        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

public function fetchReportsPaginated($limit, $offset)
{
    $stmt = $this->getConnection()->prepare("
        SELECT *
        FROM report
        ORDER BY Date_Sent DESC
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public function countAllReports()
{
    $stmt = $this->getConnection()->query("
        SELECT COUNT(*) FROM report
    ");

    return $stmt->fetchColumn();
}



}
