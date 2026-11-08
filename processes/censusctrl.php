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
                    if ($this->censusModel->createMember($_POST)) {
                        header("Location: census.php?page=$page&status=success");
                    } else {
                        header("Location: census.php?page=$page&status=error");
                    }
                    exit();
                }
                break;

            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $userId = (int)$_POST['UserID'];
                    if ($this->censusModel->updateMember($userId, $_POST)) {
                        header("Location: census.php?page=$page&status=updated");
                    } else {
                        header("Location: census.php?page=$page&status=error");
                    }
                    exit();
                }
                break;

            case 'delete':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : null; 
                if ($id) {
                    if ($this->censusModel->deleteMember($id)) {
                        header("Location: census.php?page=$page&status=deleted");
                    } else {
                        header("Location: census.php?page=$page&status=delete_failed");
                    }
                }
                exit();
                break;

            case 'bulk_delete':
                $ids = explode(',', $_GET['ids'] ?? '');
                $successCount = 0;
                foreach ($ids as $id) {
                    if (!empty($id)) {
                        if ($this->censusModel->deleteMember((int)$id)) {
                            $successCount++;
                        }
                    }
                }
                header("Location: census.php?page=$page&bulk_deleted=$successCount");
                exit();
                break;

            default:
                $searchTerm = $_GET['search'] ?? '';
                $status = in_array($_GET['status'] ?? '', ['success', 'updated', 'deleted', 'error', 'delete_failed']) ? '' : ($_GET['status'] ?? '');

                $total_records = $this->censusModel->getTotalCount($searchTerm, $status);
                $total_pages = ceil($total_records / $limit);
                
                $page = ($page > $total_pages && $total_pages > 0) ? $total_pages : $page;
                $offset = ($page - 1) * $limit;

                return [
                    'members' => $this->censusModel->getAllMembers($offset, $limit, $searchTerm, $status),
                    'total_pages' => $total_pages,
                    'page' => $page
                ];
        }
    }
}
?>