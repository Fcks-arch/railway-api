<?php
require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = $db->query("SELECT id, email, name FROM manager LIMIT 1");
    $manager = $result->fetch_assoc();
    respond(['manager' => $manager]);
}

if ($method === 'POST') {
    $d = getBody();
    if (!empty($d['update_pin'])) {
        $hash = md5($d['pin']);
        $stmt = $db->prepare("UPDATE manager SET pin_hash=? WHERE id=1");
        $stmt->bind_param("s", $hash);
        $stmt->execute();
        respond(['success' => true]);
    }
    if (!empty($d['update_gmail'])) {
        $stmt = $db->prepare("UPDATE manager SET email=? WHERE id=1");
        $stmt->bind_param("s", $d['email']);
        $stmt->execute();
        respond(['success' => true]);
    }
}
?>