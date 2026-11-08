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
    <link rel="stylesheet" href="../style/userstyle/report.css"> 
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/userstyle/mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>   

<body>
    <?php 
        $path_to_root = "../";
        include '../nav/homenav.php'; ?>

    <div class="report-main-content">
      <div class="report-container">
    <h1>Report</h1>
    <form class="report-form"
      action="../router/reporting.php"
      method="POST"
      enctype="multipart/form-data">

        
        <div class="form-body"> 
            <div class="form-left">
                <div class="input-group">
                    <select name="problem_type" required>
                        <option value="" disabled selected>Type of Problem</option>
                        <option value="Drainage">Clogged/Damaged Drainage</option>
                        <option value="Road Potholes">Road Potholes</option>
                        <option value="Rising Water Level">Rising Water Level</option>
                        <option value="Blocked Road">Blocked Road</option>
                        <option value="Standing Water">Standing Water</option>
						<option value="Other">Other</option>
                    </select>
                </div>

                <div class="input-group">
    <input
    type="text"
    name="address"
    placeholder="Enter location of the problem"
    value="<?php echo htmlspecialchars($_SESSION['located'] ?? '', ENT_QUOTES); ?>"
    required
>



                </div>

                <div class="input-group file-upload">
                    <label for="file-input" id="reportFilesLabel">+ Add Photo for the problem</label>
                    <input type="file" id="file-input" name="report_files[]" multiple>
                </div>
            </div>

            <div class="form-right">
                <label class="desc-label">Elaborate on the issue</label>
                <textarea name="description" placeholder="Write details here..." required></textarea>
            </div>
        </div>

        <div class="form-footer">
            <button type="submit" name="submit_report" class="btn-report-submit">Report</button>
        </div>
    </form>
</div>
    </div>
    <script src="../js/profile.js"></script>
    <?php 
        $path_to_root = "../";
        include '../nav/footer.php'; ?>
</body>

</html>