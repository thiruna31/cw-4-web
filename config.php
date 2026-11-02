<?php
// Practice-only configuration: fictional information; no real payments.
define('RECORD_FILE', __DIR__ . '/data/submissions.csv');
function clean($value) { return is_string($value) || is_numeric($value) ? trim((string)$value) : ''; }
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function pageStart($title) { echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($title).' | CW04</title><link rel="stylesheet" href="assets/style.css"></head><body><div class="wrap"><header><h1>CW04: PHP Forms Lab</h1><p>Practice workspace • fictional data only</p></header><main>'; }
function pageEnd() { echo '</main><footer>CSC Web Programming • CW04</footer></div></body></html>'; }
