<?php
require_once __DIR__ . '/../bootstrap.php';
appStartSession();
appRequireAuthenticatedUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/scrollbar.css">
    <link rel="stylesheet" href="../style/userstyle/profile.css"> 
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/userstyle/mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
</head>   

<body>
<?php 
$path_to_root = "../";
include '../nav/homenav.php'; 
?>

<div class="whole-container">

    <!-- BLUE HEADER -->
    <div class="blue-bg">
        <div class="white-profile">
            <div class="left-prof">
                <i class="fa-solid fa-circle-user" id="profileIcon-edit"></i>
                <div class="name-status-container">
                    <div class="profile-name"><?php echo $_SESSION["firstName"]." ".$_SESSION["lastName"]?></div>
                    <div class="status">
                        Vulnerability Status: <span id="riskText"><?= $_SESSION["risk_grade"] ?></span><br>
                        Household Code: <span class="code"><?php echo $_SESSION["householdid"]; ?></span>
                    </div>
                </div>
            </div>
            <button class="btn-out" onclick="window.location.href='../index.php';">Sign out</button> 
        </div>

        <div class="switch-tabs">
            <nav class="profileheader-links">
                <a href="profile.php" class="active">Profile</a>
                <a href="history.php">Reports</a>
                <a href="privacy-policy.php">Privacy Policy</a>
            </nav>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="profile-main-grid">

        <!-- LEFT SIDE -->
        <section class="profiles-content-area">
            <form class="profiles-form" id="profileForm" method="POST" action="../router/profileupdrouter.php">

                <!-- PERSONAL DETAILS -->
                <div class="profiles-section-header">
                    <h2 class="profiles-section-title">Personal Details</h2>
                </div>

                <div class="profiles-form-row">
                    <div class="profiles-input-group">
                        <label>First Name</label>
                        <input type="text" class="profiles-input-field" name="firstname"  readonly value= "<?php echo $_SESSION["firstName"]?>">
                    </div>
                    <div class="profiles-input-group">
                        <label>Last Name</label>
                        <input type="text" class="profiles-input-field" name="lastname" readonly value="<?php echo $_SESSION["lastName"]?>">
                    </div>
                    <div class="profiles-input-group">
                        <label>Date of Birth</label>
                        <input type="date" class="profiles-input-field" name="dateofbirth" readonly value="<?php echo $_SESSION["dateofbirth"]?>">
                    </div>
             

                      <div class="profiles-input-group">
                        <label>Contact Number</label>
                        <input type="text" class="profiles-input-field" name="contactnum" readonly value="<?php echo $_SESSION["contactnum"]?>">
                    </div>
                </div>

                <div class="profiles-form-row">
                    <div class="profiles-input-group">
                        <label>Status</label>
                        <select name="status" class="profiles-select-field" disabled>
                            <option value="Adult" <?php if($_SESSION["status"] == "Adult") echo "selected"; ?>>Adult</option>
                            <option value="PWD" <?php if($_SESSION["status"] == "PWD") echo "selected"; ?>>PWD</option>
							<option value="Child" <?php if($_SESSION["status"] == "Child") echo "selected"; ?>>Child</option>
                            <option value="Senior Citizen" <?php if($_SESSION["status"] == "Senior Citizen") echo "selected"; ?>>Senior Citizen</option>
                        </select>
                    </div>
                    <div class="profiles-input-group">
                        <label>Address</label>
                        <input type="text" class="profiles-input-field" name="address" readonly value="<?php echo $_SESSION["address"]; ?>">
                    </div>
                </div>
   <div class="area-card">
                <label>Area</label>
                        <select id="areaEdit" name="area" class="profiles-select-field" disabled>
                            <option value="Celine Homes" <?php if($_SESSION["area_name"] == "Celine Homes") echo "selected"; ?>>Celine Homes</option>
                            <option value="East Homes" <?php if($_SESSION["area_name"] == "East Homes") echo "selected"; ?>>East Homes</option>
                            <option value="Purok Carvic" <?php if($_SESSION["area_name"] == "Purok Carvic") echo "selected"; ?>>Purok Carvic</option>
                        </select>
                    <input type="hidden" name="area" id="areaHidden" value="<?= htmlspecialchars($_SESSION['area_name']) ?>">
                <div id="locEdit" class ="profile-map-edit">
                    <h3 id ="locTitle" >Household Location</h3>
                    <input type="hidden" name="latitude" id="latInput">
                    <input type="hidden" name="longitude" id="lngInput">
                    <div class="profile-map-container">
                        <div id="profile-map" 
                            data-lat="<?= htmlspecialchars($_SESSION['latitude'] ?? '') ?>" 
                            data-lng="<?= htmlspecialchars($_SESSION['longitude'] ?? '') ?>"
                            area-name="<?= htmlspecialchars($_SESSION['area_name'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
                <!-- ACCOUNT DETAILS -->
                <div class="profiles-section-header">
                    <h2 class="profiles-section-title">Account Details</h2>
                </div>

                <div class="profiles-form-row">
                    <div class="profiles-input-group">
                        <label>Email Address</label>
                        <input type="email" class="profiles-input-field" name="email" readonly value="<?php echo $_SESSION["email"]?>">
                    </div>
                    <div class="profiles-input-group">
        <label>New Password</label>
        <input type="password" name="password" class="profiles-input-field" readonly >
    </div>


        <div class="profiles-input-group">
            <label>Confirm Password</label>
            <input type="password" name="confirmpass" class="profiles-input-field" readonly>
        </div>
    </div>

                <!-- MEMBER DETAILS -->
                <div class="profiles-section-header">
                    <h2 class="profiles-section-title">Household Details</h2>
                </div>
                <?php if (!empty($_SESSION["household_members"])): ?>
                <?php foreach ($_SESSION["household_members"] as $member): ?>
                <div class="profiles-form-row">
                    <div class="profiles-input-group">
                        <label>Name</label>
                        <p class="profiles-input-field"><?= htmlspecialchars($member['First_Name'] . ' ' . $member['Last_Name']); ?></p>
                    </div>
                    <div class="profiles-input-group">
                        <label>Status</label>
                        <p class="profiles-input-field"><?= htmlspecialchars($member['User_Status']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                            <div class="profiles-input-group">
                            <p class="profiles-input-field">No Members Found</p>
                            </div>
                <?php endif; ?>
                  
            </form>
        </section>

        <!-- RIGHT SIDEBAR -->
        <aside class="profiles-sidebar">
            <div class="sidebar-card">
                <h3>Account History</h3>
                <div class="sidebar-divider"></div>
                <p>Account Created: <span><?php echo $_SESSION["datecreated"]?></span></p>
                <p>Last Updated: <span><?php echo $_SESSION["dateupdated"]?></span></p>
            </div>

            <div class="sidebar-card action-card">
               <!-- <button type="button" class="btn-download">Download Profile</button> -->
                <button type="button" id="master-edit-btn" class="btn-edit-round" onclick="enableGlobalEdit()">
                    <i class="fa-solid fa-pencil"></i> Edit Profile
                </button>
            </div>
                
                <div class="sidebar-card">
                <h3>Head Count</h3>
                <div class="sidebar-divider"></div>
                <p class = "sidebarmem"><?php 
            $count = isset($_SESSION["household_members"]) ? count($_SESSION["household_members"]) : 0;
            echo $count . ($count === 1 ? " member" : " members"); ?></p>
            </div>

            <div class="profiles-footer-actions">
                <button type="button" id="main-save-btn" class="profiles-btn-submit">
                    Save Changes
                </button>
            </div>
        </aside> 
    </div>
</div>

        <!-- SAVE CONFIRM MODAL -->
        <div id="save-confirm-modal" class="custom-modal">
            <div class="modal-content">
                <i class="fa-solid fa-circle-question modal-icon"></i>
                <h3>Save Changes?</h3>
                <p>Are you sure you want to update your profile information?</p>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel-modal" onclick="closeSaveModal()">No, Cancel</button>
                    <button type="button" class="btn-confirm-modal" onclick="submitProfileForm()">Yes, Save</button>
                </div>
            </div>
        </div>

        <!-- right side modal -->  
        <div id="customAlert" class="custom-alert">
            <span id="customAlertText"></span>
        </div>

        <script src="../js/profile.js"></script>
        <?php 
        $path_to_root = "../";
        include '../nav/footer.php'; 
        ?>
  
</body>
</html>