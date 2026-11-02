<?php
// Optional extension: counters are stored server-side; cookie carries session ID.
session_start();
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['action'] ?? '') === 'reset') {
    unset($_SESSION['cw04_visit_count']);
}
$_SESSION['cw04_visit_count'] = ($_SESSION['cw04_visit_count'] ?? 0) + 1;
require_once __DIR__ . '/config.php';
pageStart('Session demonstration');
?>
<h2>Extension C: Session counter</h2>
<p>Visits from this browser session: <strong><?= (int)$_SESSION['cw04_visit_count'] ?></strong></p>
<p>The browser keeps a session identifier; PHP keeps the counter on the server. It can reset when the session expires.</p>
<form method="post" action="mission_session.php"><input type="hidden" name="action" value="reset"><button type="submit">Reset counter</button></form>
<p><a href="index.php">Home</a></p>
<?php pageEnd(); ?>
