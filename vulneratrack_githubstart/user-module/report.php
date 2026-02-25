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
                        <option value="Power Interruption">Power Interruption</option>
                        <option value="Rising Water Level">Rising Water Level</option>
                        <option value="Blocked Road">Blocked Road</option>
                        <option value="Standing Water">Standing Water</option>
                    </select>
                </div>

                <div class="input-group">
   <select name="address" required>
     <option value="" disabled selected>Address</option>
    <option value="<?php echo $_SESSION['address']; ?>">
        <?php echo $_SESSION['address']; ?>
    </option>
</select>


                </div>

                <div class="input-group file-upload">
                    <label for="file-input" id="reportFilesLabel">+ Add Files</label>
                    <input type="file" id="file-input" name="report_files[]" multiple>
                </div>
            </div>

            <div class="form-right">
                <label class="desc-label">Elaborate on the issue</label>
                <textarea name="description" placeholder="Write details here..."></textarea>
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