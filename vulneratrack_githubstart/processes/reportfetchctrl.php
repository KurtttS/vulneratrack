<?php

require_once __DIR__ . '/../classes/reportingclass.php';

class reportfetchctrl extends reportingclass {

    private $householdID;

    public function __construct($householdID) {
        parent::__construct();
        $this->householdID = $householdID;
    }

    public function fetchReports() {
        return $this->getReportsByHousehold($this->householdID);
    }
}
