
    <link rel="stylesheet" href="../style/userstyle/profile.css"> 

<?php if (empty($reports)): ?>

    <p class = "noreps">No reports have been made.</p>

<?php else: ?>

<?php foreach ($reports as $row): ?>

<?php
    $files = [];

    if (!empty($row['Report_Image'])) {
        $files = explode(',', $row['Report_Image']);
    }

    $fileCount = count($files);
    $status = ucfirst($row['report_status']);
?>

<div class="report-card">

    <div class="report-header">
        <div>
            <h3><?= htmlspecialchars($row['Type_of_Problem']) ?></h3>
            <p class="report-date">
                Report Sent: <?= date("F j, Y", strtotime($row['Date_Sent'])) ?>
            </p>
        </div>

        <div class="report-status">
            <span class="file-count"><?= $fileCount ?> Files</span>
            <span class="status-badge <?= strtolower($status) ?>">
                <?= $status ?>
            </span>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
    </div>

    <div class="report-body">

        <p class="report-description">
            <?= nl2br(htmlspecialchars($row['Report_Message'])) ?>
        </p>

        <?php if ($fileCount > 0): ?>
        <div class="report-images">
            <?php foreach ($files as $img): ?>
                <img src="../uploads/reports/<?= htmlspecialchars($img) ?>" alt="">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>
<?php endif; ?>
