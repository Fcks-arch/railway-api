<?php
define('DB_HOST', getenv('MYSQLHOST') ?: 'maglev.proxy.rlwy.net');
define('DB_PORT', getenv('MYSQLPORT') ?: '28397');
define('DB_USER', getenv('MYSQLUSER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: 'CTcegQuMqhmYOxwkjZpipMTsmKpIDDnh');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'railway');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, (int)DB_PORT);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'DB Error: ' . $conn->connect_error]);
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
    return json_decode(file_get_contents('php://input'), true);
}
