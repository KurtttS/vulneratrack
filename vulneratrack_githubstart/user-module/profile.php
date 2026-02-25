<!DOCTYPE html>
<?php
session_start();
?>
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
                        Your Location: <?php echo $_SESSION["address"]; ?> <br>
                        Vulnerability Status: <span class="high-risk"><?= $_SESSION["risk_grade"] ?></span><br>
                         Household code: <span class="code"><?php echo $_SESSION["householdid"]; ?></span>
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
                        <label>Date of Birth</label>
                        <input type="date" class="profiles-input-field" name="dateofbirth" readonly value="<?php echo $_SESSION["dateofbirth"]?>">
                    </div>
                </div>

                <div class="profiles-form-row">
                    <div class="profiles-input-group">
                        <label>Last Name</label>
                        <input type="text" class="profiles-input-field" name="lastname" readonly value="<?php echo $_SESSION["lastName"]?>">
                    </div>
               <div class="profiles-input-group">
    <label>Status</label>
    <select name="status" class="profiles-select-field" disabled>
        <option value="Adult" <?php if($_SESSION["status"] == "Adult") echo "selected"; ?>>Adult</option>
        <option value="PWD" <?php if($_SESSION["status"] == "PWD") echo "selected"; ?>>PWD</option>
        <option value="Senior Citizen" <?php if($_SESSION["status"] == "Senior Citizen") echo "selected"; ?>>Senior Citizen</option>
    </select>
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
                <button type="button" class="btn-download">Download Profile</button>
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