<?php
require_once __DIR__ . '/config.php';
$msg = $_POST['message'] ?? '';
$error = null;
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!is_string($msg) || trim($msg) === '' || strlen($msg) > 300) $error = 'Enter 1–300 characters of fictional text.';
}
pageStart('Safe preview');
?>
<h2>Extension A: Escaped-text preview</h2>
<p>Enter invented text to see how a PHP server can show user input safely without saving it.</p>
<form action="mission_preview.php" method="post">
<label for="message">Fictional message</label>
<textarea id="message" name="message" rows="4" maxlength="300" required><?= e(is_string($msg) ? $msg : '') ?></textarea>
<button type="submit">Preview without saving</button>
</form>
<?php if ($error): ?><p class="error"><?=e($error)?></p>
<?php elseif (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'): ?>
<div class="note"><strong>Safe output:</strong><p class="preview"><?= e($msg) ?></p></div>
<?php endif; ?>
<p><a href="index.php">Home</a></p>
<?php pageEnd(); ?>
