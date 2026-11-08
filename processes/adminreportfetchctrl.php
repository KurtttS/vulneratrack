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

public function fetchReportsPaginated($limit, $offset, $search = '', $status = '')
{
    $sql = "SELECT * FROM report WHERE 1=1";

    if (!empty($search)) {
        $sql .= " AND (report_address LIKE :search 
                   OR located LIKE :search 
                   OR Type_of_Problem LIKE :search)";
    }

    if (!empty($status)) {
        $sql .= " AND report_status = :status";
    }

    $sql .= " ORDER BY Date_Sent DESC LIMIT :limit OFFSET :offset";

    $stmt = $this->getConnection()->prepare($sql);

    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }

    if (!empty($status)) {
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function countFilteredReports($search = '', $status = '')
{
    $sql = "SELECT COUNT(*) FROM report WHERE 1=1";

    if (!empty($search)) {
        $sql .= " AND (report_address LIKE :search 
                   OR located LIKE :search 
                   OR Type_of_Problem LIKE :search)";
    }

    if (!empty($status)) {
        $sql .= " AND report_status = :status";
    }

    $stmt = $this->getConnection()->prepare($sql);

    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }

    if (!empty($status)) {
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchColumn();
}

public function countAllReports()
{
    $stmt = $this->getConnection()->query("
        SELECT COUNT(*) FROM report
    ");

    return $stmt->fetchColumn();
}

}
