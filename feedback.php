<?php
require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT f.*, b.name as branch_name FROM feedback f LEFT JOIN branches b ON f.branch_id=b.id ORDER BY f.created_at DESC";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    respond($rows);
}

if ($method === 'POST') {
    $d = getBody();
    if (!empty($d['id']) && isset($d['reply'])) {
        $stmt = $db->prepare("UPDATE feedback SET replied=1, reply=? WHERE id=?");
        $stmt->bind_param("si", $d['reply'], $d['id']);
        $stmt->execute();
        respond(['success' => true]);
    } else {
        $stmt = $db->prepare("INSERT INTO feedback (customer_name,branch_id,rating,comment) VALUES (?,?,?,?)");
        $stmt->bind_param("siis", $d['customer_name'],$d['branch_id'],$d['rating'],$d['comment']);
        $stmt->execute();
        respond(['success' => true, 'id' => $db->insert_id]);
    }
}
?>