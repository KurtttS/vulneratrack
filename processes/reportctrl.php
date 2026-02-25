<?php

require_once __DIR__ . '/../classes/reportingclass.php';

class reportctrl extends reportingclass {


    private $type;
    private $description;
    private $files;
    private $householdID;
    private $report_address;


    public function __construct($type, $description, $files, $householdID, $report_address) {
        parent::__construct();
        $this->type = $type;
        $this->description = $description;
        $this->files = $files;
        $this->householdID = $householdID;
        $this->report_address = $report_address;
    }

    public function submitReport() {

        if ($this->emptyInput() == false) {
            header("location: ../user-module/report.php?error=empty");
            exit();
        }

        $storedFiles = $this->uploadFiles();

        $this->createReport(
            $this->type,
            $this->description,
            $storedFiles,
            $this->householdID,
            $this->report_address
        );
    }

    private function emptyInput() {

        if (
            empty($this->type) ||
            empty($this->description)
        ) {
            return false;
        }

        return true;
    }

    private function uploadFiles() {

        $saved = [];

        if (!empty($this->files['name'][0])) {

            $uploadPath = __DIR__ . "/../uploads/reports/";

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            for ($i = 0; $i < count($this->files['name']); $i++) {

                if ($this->files['error'][$i] === 0) {

                    $newName = time() . "_" . basename($this->files['name'][$i]);

                    move_uploaded_file(
                        $this->files['tmp_name'][$i],
                        $uploadPath . $newName
                    );

                    $saved[] = $newName;
                }
            }
        }

        return implode(",", $saved);
    }
}
?>