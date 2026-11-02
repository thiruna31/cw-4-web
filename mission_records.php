<?php
// Optional extension: non-sensitive CSV audit. Does not reveal submitted records.
require_once __DIR__ . '/config.php';
$recordCount = 0; $lastTimestamp = null; $problem = null;
$h = @fopen(RECORD_FILE, 'r');
if ($h !== false) {
    if (flock($h, LOCK_SH)) {
        $header = fgetcsv($h);
        if ($header !== false) {
            while (($row = fgetcsv($h)) !== false) {
                if (count($row) >= 5) { ++$recordCount; $lastTimestamp = $row[0]; }
            }
        }
        flock($h, LOCK_UN);
    } else $problem = 'Could not lock the CSV file for reading.';
    fclose($h);
} else $problem = 'The CSV file could not be read by PHP. Check CODD permissions.';
pageStart('CSV audit');
?>
<h2>Extension B: Record count audit</h2>
<p>This page counts saved practice requests without exposing names, IDs or emails. The count changes only when PHP accepts a submission.</p>
<?php if ($problem): ?><p class="error"><?= e($problem) ?></p><?php else: ?>
<p class="metric">Total valid practice records: <strong><?= $recordCount ?></strong></p>
<p>Most recent submission (UTC): <?= e($lastTimestamp ?? 'None yet') ?></p>
<?php endif; ?>
<p><a href="buy_grade.php">Try the form</a> · <a href="index.php">Home</a></p>
<?php pageEnd(); ?>
