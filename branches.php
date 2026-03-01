<?php
require_once 'cors.php';
require_once 'config.php';
// ... rest of your code

require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = $db->query("SELECT * FROM branches ORDER BY id");
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    respond($rows);
}

if ($method === 'POST') {
    $d = getBody();
    $stmt = $db->prepare("UPDATE branches SET status=? WHERE id=?");
    $stmt->bind_param("si", $d['status'], $d['id']);
    $stmt->execute();
    respond(['success' => true]);
}

if ($method === 'PUT') {
    $d = getBody();
    $stmt = $db->prepare("UPDATE branches SET name=?,address=?,contact=?,open_time=?,close_time=?,status=? WHERE id=?");
    $stmt->bind_param("ssssssi", $d['name'],$d['address'],$d['contact'],$d['open_time'],$d['close_time'],$d['status'],$d['id']);
    $stmt->execute();
    respond(['success' => true]);
}
?>
