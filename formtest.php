<?php
require_once __DIR__ . '/config.php';
pageStart('Feature selection result');
?>
<h2>Submitted features</h2>
<?php
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    echo '<p class="error">Please use the feature-selection form.</p>';
} else {
    $name = clean($_POST['fictional_name'] ?? '');
    $chosen = $_POST['features'] ?? [];
    $allowed = ['Chat', 'Email', 'Shell'];
    $errors = [];
    if ($name === '' || strlen($name) > 70) $errors[] = 'Please enter a fictional name.';
    if (!is_array($chosen)) {
        $errors[] = 'Invalid feature format: expected several or zero selections.';
    } else {
        foreach ($chosen as $feature) {
            if (!is_string($feature) || !in_array($feature, $allowed, true)) {
                $errors[] = 'Unknown or malformed feature submitted.';
                break;
            }
        }
        $chosen = array_values(array_unique($chosen));
    }
    if ($errors) {
        echo '<p class="error">Submission not accepted.</p><ul>';
        foreach ($errors as $error) echo '<li>'.e($error).'</li>';
        echo '</ul>';
    } else {
        echo '<p>Submitted by: <strong>'.e($name).'</strong></p>';
        if (!$chosen) {
            echo '<p>No optional features were selected.</p>';
        } else {
            echo '<p>'.count($chosen).' feature(s) selected:</p><ul>';
            foreach ($chosen as $feature) echo '<li>'.e($feature).'</li>';
            echo '</ul>';
        }
    }
}
?>
<p><a href="formtest.html">Try another selection</a></p>
<?php pageEnd(); ?>
