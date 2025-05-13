<?php
// Display PHP session cookie settings and session configuration
echo "<h2>PHP Session Cookie Settings</h2>";

echo "<pre>";
echo "session.cookie_lifetime: " . ini_get('session.cookie_lifetime') . "\\n";
echo "session.cookie_path: " . ini_get('session.cookie_path') . "\\n";
echo "session.cookie_domain: " . ini_get('session.cookie_domain') . "\\n";
echo "session.cookie_secure: " . ini_get('session.cookie_secure') . "\\n";
echo "session.cookie_httponly: " . ini_get('session.cookie_httponly') . "\\n";
echo "session.use_strict_mode: " . ini_get('session.use_strict_mode') . "\\n";
echo "session.use_cookies: " . ini_get('session.use_cookies') . "\\n";
echo "session.use_only_cookies: " . ini_get('session.use_only_cookies') . "\\n";
echo "session.gc_maxlifetime: " . ini_get('session.gc_maxlifetime') . "\\n";
echo "</pre>";
?>
