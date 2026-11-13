<?php

require_once __DIR__ . '/../classes/reportingclass.php';
require_once __DIR__ . '/../classes/supabasestorage.php';

class reportctrl extends reportingclass {


    private $type;
    private $description;
    private $files;
    private $householdID;
    private $report_address;
    private $located;
    private SupabaseStorage $storage;


    public function __construct($type, $description, $files, $householdID, $report_address, $located) {
        parent::__construct();
        $this->type = $type;
        $this->description = $description;
        $this->files = $files;
        $this->householdID = $householdID;
        $this->report_address = $report_address;
        $this->located = $located;
        $this->storage = new SupabaseStorage();
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
            $this->report_address,
             $this->located
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

            for ($i = 0; $i < count($this->files['name']); $i++) {
                if ($this->files['error'][$i] !== UPLOAD_ERR_OK) {
                    throw new RuntimeException('A report attachment could not be uploaded.');
                }

                $temporaryPath = $this->files['tmp_name'][$i];
                if (!is_uploaded_file($temporaryPath)) {
                    throw new RuntimeException('A report attachment is invalid.');
                }

                $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($temporaryPath);
                if (!is_string($mimeType)) {
                    throw new RuntimeException('Unable to determine an attachment file type.');
                }

                $saved[] = $this->storage->uploadReportFile($temporaryPath, $mimeType);
            }
        }

        return implode(",", $saved);
    }
}
?>