<?php
$phpIniPath = 'c:/xampp/php/php.ini';
$phpIniContent = file_get_contents($phpIniPath);
$phpIniContent = str_replace(';extension=gd', 'extension=gd', $phpIniContent);
file_put_contents($phpIniPath, $phpIniContent);
echo "GD extension enabled. Please restart Apache server.";
?>