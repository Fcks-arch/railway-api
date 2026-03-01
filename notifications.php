<?php
require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT n.*, b.name as branch_name FROM notifications n LEFT JOIN branches b ON n.branch_id=b.id ORDER BY n.created_at DESC";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    respond($rows);
}

if ($method === 'POST') {
    $d = getBody();
    if (!empty($d['mark_all'])) {
        $db->query("UPDATE notifications SET is_read=1");
        respond(['success' => true]);
    } else {
        $stmt = $db->prepare("UPDATE notifications SET is_read=1 WHERE id=?");
        $stmt->bind_param("i", $d['id']);
        $stmt->execute();
        respond(['success' => true]);
    }
}
?>