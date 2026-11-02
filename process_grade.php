<?php
require_once __DIR__ . '/config.php';
pageStart('Form processing result');
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    echo '<h2>Submit the form first</h2><p><a href="buy_grade.php">Open the form</a></p>';
    pageEnd(); exit;
}
$name = clean($_POST['student_name'] ?? '');
$email = clean($_POST['student_email'] ?? '');
$id = strtoupper(clean($_POST['student_id'] ?? ''));
$grade = clean($_POST['requested_grade'] ?? '');
$errors = [];
if ($name === '' || strlen($name) > 70) $errors[] = 'Enter a fictional name (up to 70 characters).';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 120) $errors[] = 'Enter a valid practice email.';
if (!preg_match('/^[A-Z0-9-]{4,16}$/', $id)) $errors[] = 'Use a fictional student ID of 4–16 letters, numbers or hyphens.';
if (!in_array($grade, ['A', 'B', 'C', 'D', 'F'], true)) $errors[] = 'Choose an allowed grade (A, B, C, D or F).';
if ($errors) {
    echo '<h2 class="error">Please correct the form</h2><ul>';
    foreach ($errors as $error) echo '<li>'.e($error).'</li>';
    echo '</ul><p>No record was saved.</p><p><a href="buy_grade.php">Back to form</a></p>';
    pageEnd(); exit;
}
function csvSafe($value) {
    return preg_match('/^[=+@\-\t\r]/u', $value) ? "'".$value : $value;
}
$handle = @fopen(RECORD_FILE, 'c+');
if ($handle === false) {
    echo '<h2 class="error">Storage unavailable</h2><p>The data folder may need server-write permission.</p>';
    pageEnd(); exit;
}
if (!flock($handle, LOCK_EX)) {
    fclose($handle);
    echo '<h2 class="error">Storage unavailable</h2>';
    pageEnd(); exit;
}
$size = fstat($handle)['size'] ?? 0;
fseek($handle, 0, SEEK_END);
$ok = ($size > 0) || fputcsv($handle, ['Submitted (UTC)', 'Name', 'Email', 'Student ID', 'Grade']) !== false;
if ($ok) $ok = fputcsv($handle, [gmdate('Y-m-d H:i:s'), csvSafe($name), csvSafe($email), $id, $grade]) !== false;
fflush($handle);
flock($handle, LOCK_UN);
fclose($handle);
if (!$ok) {
    echo '<h2 class="error">Could not save the record</h2>';
    pageEnd(); exit;
}
echo '<h2 class="success">Practice request saved</h2><p>Validated values:</p><ul>';
foreach (['Name'=>$name, 'Email'=>$email, 'Student ID'=>$id, 'Requested grade'=>$grade] as $label=>$value) {
    echo '<li><strong>'.e($label).':</strong> '.e($value).'</li>';
}
echo '</ul><p>One CSV row was appended.</p><p><a href="buy_grade.php">Try another</a></p>';
pageEnd();
