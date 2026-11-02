<?php require_once __DIR__ . '/config.php'; pageStart('Selected features'); ?>
<h2>Selected features</h2>
<?php
$allowed = ['Search', 'Dark mode', 'Notifications', 'Reports'];
$chosen = $_POST['features'] ?? [];
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo '<p class="error">Please submit the features form first.</p>'; }
elseif (!is_array($chosen)) { echo '<p class="error">Unexpected selection format.</p>'; }
else {
 $chosen = array_values(array_unique(array_filter($chosen, 'is_string')));
 $chosen = array_values(array_intersect($allowed, $chosen));
 if (!$chosen) { echo '<p>No features selected.</p>'; }
 else { echo '<p>You selected '.count($chosen).' feature(s):</p><ul>'; foreach ($chosen as $value) { echo '<li>'.e($value).'</li>'; } echo '</ul>'; }
}
?>
<p><a href="features.php">Try again</a></p><?php pageEnd(); ?>