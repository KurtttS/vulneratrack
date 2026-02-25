<?php

require_once __DIR__ . '/../db/database.php';

class reportingclass extends database {

    protected function createReport(
        $type,
        $description,
        $images,
        $householdID,
        $report_address
    ) {

        $stmt = $this->getConnection()->prepare(
            "INSERT INTO report
            (Date_Sent, Report_Message, Type_of_Problem, Report_Image, Household_ID, report_address, report_status)
            VALUES (NOW(), ?, ?, ?, ?, ?, 'unverified')"
        );

        $stmt->execute([
            $description,
            $type,
            $images,
            $householdID,
            $report_address
        ]);
    }

    protected function getReportsByHousehold($householdID)
    {
        $stmt = $this->getConnection()->prepare(
            "SELECT *
             FROM report
             WHERE Household_ID = ?
             ORDER BY Date_Sent DESC"
        );

        $stmt->execute([$householdID]);

        return $stmt->fetchAll();
    }

    /* ===============================
       DELETE
       =============================== */

    protected function deleteReportById($id)
    {
        $stmt = $this->getConnection()->prepare(
            "DELETE FROM report WHERE ReportID = ?"
        );

        return $stmt->execute([$id]);
    }

    public function deleteReport($id)
    {
        return $this->deleteReportById($id);
    }

}
