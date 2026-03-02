<?php
error_reporting(0);
ini_set('display_errors', 0);


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

function getDB() {
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @new mysqli(
        'shortline.proxy.rlwy.net',
        'root',
        'rEqexMabCkBNXIOIQnMbiPCbCIRtMrjO',
        'railway',
        18972
    );
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => $conn->connect_error]);
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
