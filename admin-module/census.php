<?php
require_once '../processes/censusctrl.php';
$controller = new CensusCtrl();
$data = $controller->handleRequest();

$membersList = $data['members'];
$total_pages = $data['total_pages'];
$page = $data['page'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/adminstyle/census.css">
    <link rel="stylesheet" href="../style/adminstyle/adminmobile.css">
    <link rel="stylesheet" href="../style/adminstyle/sidebar.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
    <div class="page-container">
        <?php 
            $path_to_root = "../";
            include '../nav/sidebar.php'; 
        ?>

        <div class="adminpage-container">
            <div class="header-container">
                <div class="census-badge">Census</div>
                <div class="header-line"></div>
            </div>

            <div class="controls">
               
                
                <button id="deleteSelectedBtn" class="action-btn delete">
                    <i class="fas fa-trash"></i> Delete Selected
                </button>
                
                <button class="add-btn" onclick="toggleModal()">+</button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>User ID</th>    
                            <th>Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Date of Birth</th>
                            <th>Status</th>
                            <th>Household ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="censusTableBody">
                        <?php if (!empty($membersList)): ?>
                            <?php foreach($membersList as $row): ?>
                                <tr>
                                    <td><input type="checkbox" class="row-checkbox" value="<?= $row['UserID']; ?>"></td>
                                    <td><?= $row['UserID']; ?></td>
                                    <td><?= htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']); ?></td>
                                    <td><?= htmlspecialchars($row['Address']); ?></td>
                                    <td><?= htmlspecialchars($row['Email']); ?></td>
                                    <td><?= $row['Date_of_Birth']; ?></td>
                                    <td>
                                        <span class="status-pill <?= strtolower(str_replace(' ', '-', $row['User_Status'])); ?>">
                                            <?= $row['User_Status']; ?>
                                        </span>
                                    </td>
                                    <td><?= $row['Household_ID'] ?? 'None'; ?></td>
                                    <td class="action-cell">
                                        <div class="action-wrapper">
                                            <button class="action-icon edit-icon edit-btn" title="Edit Member">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9" style="text-align:center;">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <a href="census.php?page=<?= max(1, $page - 1); ?>" class="<?= ($page <= 1) ? 'disabled' : ''; ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>

                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="census.php?page=<?= $i; ?>" class="<?= ($page == $i) ? 'active' : ''; ?>">
                        <?= $i; ?>
                    </a>
                <?php endfor; ?>

                <a href="census.php?page=<?= min($total_pages, $page + 1); ?>" class="<?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>

            <?php include '../nav/footer.php'; ?>
        </div> 
    </div> 

    <div id="censusModal" class="modal-overlay" style="display: none; align-items: center; justify-content: center;">
        <div class="modal-content">
            <h2 id="modalTitle" style="color: #2d3e75; margin-bottom: 20px;">Census Member</h2>
            <form id="censusForm" method="POST" action="census.php?action=add" autocomplete="off">
                <input type="hidden" id="editUserId" name="UserID">
                
                <div class="form-row">
                    <div class="form-group flex-1">
                        <input type="text" id="firstName" name="first_name" placeholder="First Name" required>
                    </div>
                    <div class="form-group flex-1">
                        <input type="text" id="lastName" name="last_name" placeholder="Last Name" required>
                    </div>
                </div>

                <div class="form-group">
                    <input type="text" id="address" name="address" placeholder="Residential Address" required>
                </div>

                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email Address" required>
                </div>

                <div class="form-group">
                    <input type="file" name="birthcert" id="birth-cert" required>
                </div>

                <div class="form-row">
                    <div class="form-group flex-1">
                        <input type="date" id="dob" name="dob" title="Date of Birth" required>
                    </div>
                    <div class="form-group flex-1">
                        <select id="status" name="status" required>
                            <option value="" disabled selected>Status</option>
                            <option value="Adult">Adult</option>
                            <option value="Child">Child</option>
                            <option value="Senior Citizen">Senior Citizen</option>
                            <option value="PWD">PWD</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <select name="houseChoice" id="householdAction" onchange="toggleHouseholdUI()" required>
                        <option value="join">Join Existing Household</option>
                        <option  value="create">Create New Household</option>
                    </select>
                </div>

                <div id="joinSection">
                    <div class="form-group">
                        <input type="number" id="household_id" name="household_id" placeholder="Household ID Number">
                    </div>
                </div>

                <div id="createSection" style="display:none;">
                    <div class="form-group flex-1">
                        <select id="area" name="area">
                            <option value="" disabled selected>Area</option>
                            <option value="Celine Homes">Celine Homes</option>
                            <option value="East Homes">East Homes</option>
                            <option value="Mandalagan, Bacolod City">Mandalagan</option>
                        </select>
                    </div>
                    <div id="map" style="height: 180px; width: 100%; margin-bottom: 10px; border-radius: 12px; border: 1.5px solid #333;"></div>
                    <input type="hidden" id="coordX" name="lng">
                    <input type="hidden" id="coordY" name="lat">
                </div>

                <div class="form-actions">
                    <button type="submit" class="create-btn">Save Member</button>
                    <button type="button" class="cancel-btn" onclick="toggleModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="../js/census.js"></script>
</body>
</html>