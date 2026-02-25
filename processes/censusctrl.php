<?php
require_once '../classes/censusclass.php';

class CensusCtrl {
    private $censusModel;

    public function __construct() {
        $this->censusModel = new CensusClass();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? 'list';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        switch ($action) {
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->censusModel->createMember($_POST);
                    header("Location: census.php?page=$page");
                    exit();
                }
                break;

            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->censusModel->updateMember($_POST['UserID'], $_POST);
                    header("Location: census.php?page=$page");
                    exit();
                }
                break;

            case 'delete':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $this->censusModel->deleteMember($id);
                }
                header("Location: census.php?page=$page");
                exit();
                break;

            case 'bulk_delete':
                $ids = explode(',', $_GET['ids'] ?? '');
                foreach ($ids as $id) {
                    if (!empty($id)) {
                        $this->censusModel->deleteMember((int)$id);
                    }
                }
                header("Location: census.php?page=$page");
                exit();
                break;

            default:
                $members = $this->censusModel->getAllMembers($offset, $limit);
                $total_records = $this->censusModel->getTotalCount();
                $total_pages = ceil($total_records / $limit);
                return ['members' => $members, 'total_pages' => $total_pages, 'page' => $page];
        }
    }
}
?>