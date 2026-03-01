<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'MYSQLHOST' => getenv('MYSQLHOST'),
    'MYSQLPORT' => getenv('MYSQLPORT'),
    'MYSQLUSER' => getenv('MYSQLUSER'),
    'MYSQLDATABASE' => getenv('MYSQLDATABASE'),
    'MYSQLPASSWORD_SET' => getenv('MYSQLPASSWORD') ? 'yes' : 'no',
]);
