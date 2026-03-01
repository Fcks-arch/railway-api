<?php
// Suppress all PHP errors from outputting HTML
error_reporting(0);
ini_set('display_errors', 0);

// Always output JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Use environment variables set in Railway
$DB_HOST = getenv('MYSQLHOST') ?: 'maglev.proxy.rlwy.net';
$DB_PORT = (int)(getenv('MYSQLPORT') ?: '28397');
$DB_USER = getenv('MYSQLUSER') ?: 'root';
$DB_PASS = getenv('MYSQLPASSWORD') ?: 'CTcegQuMqhmYOxwkjZpipMTsmKpIDDnh';
$DB_NAME = getenv('MYSQLDATABASE') ?: 'railway';

function getDB() {
    global $DB_HOST, $DB_PORT, $DB_USER, $DB_PASS, $DB_NAME;
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => $conn->connect_error, 'host' => $DB_HOST, 'port' => $DB_PORT]);
        exit();
    }
    $conn->set_charset('utf8');
    return $conn;
}

function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit();
}

function getBody() {
    return json_decode(file_get_contents('php://input'), true) ?? [];
}












