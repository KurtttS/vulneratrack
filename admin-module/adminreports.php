<?php
include __DIR__ . '/../db/database.php';
include __DIR__ . '/../classes/reportingclass.php';
include __DIR__ . '/../processes/adminreportfetchctrl.php';

$fetch = new adminreportfetchctrl();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

$limit  = 10;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

$reports = $fetch->fetchReportsPaginated($limit, $offset, $search, $status);
$total_reports = $fetch->countFilteredReports($search, $status);
$total_pages = ceil($total_reports / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/adminstyle/assessment.css">
    <link rel="stylesheet" href="../style/adminstyle/adminmobile.css">
    <link rel="stylesheet" href="../style/adminstyle/sidebar.css">
    <link rel="stylesheet" href="../style/adminstyle/adminreports.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
    <div class="page-container">
        <?php 
        $path_to_root = "../";
        include '../nav/sidebar.php'; ?> 
        
        <div class="adminpage-container">
            <div class="content-wrapper">
                <div class="assessment-container">
            
                    <div class="dashboard-header">
                        
                        <div class="title-badge">Reports</div>
                        <div class="header-line"></div>
                    </div>
                    
        <form method="GET" class="filter-search-bar">
            <input type="text" name="search" placeholder="Search address, problem..." 
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">

            <select name="status">
                <option value="">All Status</option>
                <option value="unverified" <?= (($_GET['status'] ?? '') == 'unverified') ? 'selected' : '' ?>>Unverified</option>
                <option value="ongoing" <?= (($_GET['status'] ?? '') == 'ongoing') ? 'selected' : '' ?>>Ongoing</option>
				<option value="resolved" <?= (($_GET['status'] ?? '') == 'resolved') ? 'selected' : '' ?>>Resolved</option>
            </select>

            <button type="submit">Search</button>
        </form>
                            <div class="report-table-container">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Household id</th>
                                    <th>Household Address</th>
                                    <th>Problem located</th>
                                    <th>Report Type</th>
                                    <th>Download</th>
                                    <th>Remove row</th>
                                </tr>
                            </thead>
                           <tbody>
<?php if (!empty($reports)): ?>
    
    <?php foreach ($reports as $row): ?>
        <?php $status = ucfirst($row['report_status']); ?>
        <tr class="report-row" onclick="location.href='reportdetails.php?id=<?= $row['ReportID'] ?>'">
            <td><?= $status ?></td>
            <td><?= date("n/j/Y", strtotime($row['Date_Sent'])) ?></td>
            <td><?= htmlspecialchars($row['Household_ID']) ?></td>
            <td><?= htmlspecialchars($row['report_address']) ?></td>
            <td><?= htmlspecialchars($row['located']) ?></td>
            <td><?= htmlspecialchars($row['Type_of_Problem']) ?></td>

            <?php
            $images = explode(',', $row['Report_Image']);
            $firstImage = $images[0] ?? '';
            ?>

            <td>
                <?php if ($firstImage): ?>
                    <i class="far fa-file-alt btn-download"
                       style="cursor:pointer"
                       onclick="event.stopPropagation(); openImageModal('<?= $firstImage ?>')"></i>
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>

            <td>
                <button type="button" class="delete-report-btn" data-id="<?= $row['ReportID'] ?>">
                    Delete
                </button>
            </td>
        </tr>
    <?php endforeach; ?>

<?php else: ?>

    <tr>
        <td colspan="8" class="no-data-row">
            No reports found
        </td>
    </tr>

<?php endif; ?>
</tbody>
                        </table>
                    </div>

                    <div class="pagination">
                        <a href="?page=<?= max(1, $page - 1); ?>" class="<?= ($page <= 1) ? 'disabled' : ''; ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <span class="active"><?= $page; ?></span>
                        <a href="?page=<?= min($total_pages, $page + 1); ?>" class="<?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <div id="deleteModal" class="delete-modal" style="display:none;">
                    <div class="delete-modal-box">
                        <p>Are you sure you want to delete this report?</p>
                        <div class="modaldel">
                            <button type="button" id="cancelDelete">Cancel</button>
                            <button type="button" id="confirmDelete">Delete</button>
                        </div>
                    </div>
                </div>

                <div id="imageModal" class="image-modal">
                    <span class="close-modal" onclick="closeImageModal()">&times;</span>
                    <img id="modalImage">
                </div>

                <script src="../js/reportdel.js"></script>
            </div>
            
            <?php
                $path_to_root = "../";
                include '../nav/footer.php'; 
            ?> 
        </div>
    </div>
</body>
</html>