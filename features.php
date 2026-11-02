<?php require_once __DIR__ . '/config.php'; pageStart('Feature selector'); ?>
<h2>Exercise 1 — Multiple feature selections</h2><p>Select several options. The <code>features[]</code> name sends an array to PHP.</p>
<form method="post" action="features_result.php"><fieldset><legend>Choose features</legend>
<label><input type="checkbox" name="features[]" value="Search"> Search</label>
<label><input type="checkbox" name="features[]" value="Dark mode"> Dark mode</label>
<label><input type="checkbox" name="features[]" value="Notifications"> Notifications</label>
<label><input type="checkbox" name="features[]" value="Reports"> Reports</label>
</fieldset><button type="submit">Show my selections</button></form><p><a href="index.php">Back to home</a></p>
<?php pageEnd(); ?>