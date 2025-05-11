<?php
$httpConfPath = 'c:/xampp/apache/conf/httpd.conf';
$httpConfContent = file_get_contents($httpConfPath);
$httpConfContent = str_replace('Include conf/extra/httpd-vhosts-gobilibrary.conf', '# Include conf/extra/httpd-vhosts-gobilibrary.conf', $httpConfContent);
file_put_contents($httpConfPath, $httpConfContent);
echo "Virtual host configuration commented out. Please restart Apache server.";
?>