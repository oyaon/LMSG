<?php
$logFile = __DIR__ . '/../logs/security.log';

if (file_exists($logFile)) {
    echo "<h2>Security Log</h2>";
    echo "<pre style='white-space: pre-wrap; word-wrap: break-word;'>";
    echo htmlspecialchars(file_get_contents($logFile));
    echo "</pre>";
} else {
    echo "Security log file not found.";
}
?>
