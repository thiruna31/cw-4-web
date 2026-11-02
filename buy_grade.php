<?php require_once __DIR__ . '/config.php'; pageStart('Buy-a-Grade classroom demo'); ?>
<h2>Exercises 2–5 — Form and record handling</h2>
<p class="note">Fictional classroom simulation, not a real service. Do not enter actual student details or payment information.</p>
<form action="process_grade.php" method="post">
<label for="student_name">Fictional name</label>
<input id="student_name" name="student_name" type="text" maxlength="70" required placeholder="Demo Student">
<label for="student_email">Practice email</label>
<input id="student_email" name="student_email" type="email" maxlength="120" required placeholder="demo@example.test">
<label for="student_id">Fictional student ID</label>
<input id="student_id" name="student_id" type="text" maxlength="16" pattern="[A-Za-z0-9-]{4,16}" required placeholder="DEMO1234">
<label for="requested_grade">Requested grade</label>
<select id="requested_grade" name="requested_grade" required>
<option value="">Choose a grade</option>
<option value="A">A</option><option value="B">B</option>
<option value="C">C</option><option value="D">D</option><option value="F">F</option>
</select>
<p class="small">All four fields are required. The PHP handler checks them again.</p>
<button type="submit">Send fictional request</button>
</form><p><a href="index.php">Back to home</a></p>
<?php pageEnd(); ?>
