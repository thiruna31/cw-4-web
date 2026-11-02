<?php require_once __DIR__ . '/config.php'; pageStart('Home'); ?>
<h2>Welcome to the forms lab</h2>
<p>This website follows fictional form values from HTML into PHP, validates them, and saves only accepted requests.</p>
<h3>Five core exercises</h3>
<ol class="index-list">
<li><strong>Start, debug and trace PHP.</strong> <a href="hello.php">Hello page</a> · <a href="formtest.html">Formtest multi-select</a></li>
<li><strong>Build a readable four-field form.</strong> <a href="buy_grade.php">Open the form</a></li>
<li><strong>Display processed data safely.</strong> Submit the form and inspect the confirmation.</li>
<li><strong>Save CSV records.</strong> The handler appends successful requests to a protected file.</li>
<li><strong>Validate before saving.</strong> Try invalid inputs and check the error messages.</li>
</ol>
<h3>Additional demonstrations</h3>
<p><a href="features.php">Checkbox array version</a> · <a href="mission_preview.php">Safe preview</a> · <a href="mission_records.php">Record count audit</a> · <a href="mission_session.php">Session counter</a></p>
<p class="note"><strong>Classroom simulation only.</strong> Use fictional data. Never enter real grades, passwords, student IDs or payments.</p>
<?php pageEnd(); ?>
