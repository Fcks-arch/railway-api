<?php
require_once 'cors.php';
require_once 'config.php';
// ... rest of your code

require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT r.*, b.name as branch_name FROM reservations r LEFT JOIN branches b ON r.branch_id=b.id ORDER BY r.res_date DESC, r.res_time DESC";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    respond($rows);
}

if ($method === 'POST') {
    $d = getBody();
    if (!empty($d['id'])) {
        // Update status
        $stmt = $db->prepare("UPDATE reservations SET status=? WHERE id=?");
        $stmt->bind_param("si", $d['status'], $d['id']);
        $stmt->execute();
        respond(['success' => true]);
    } else {
        // New reservation
        $stmt = $db->prepare("INSERT INTO reservations (guest_name,branch_id,res_date,res_time,guests,table_num,contact,notes,status) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sissssss", $d['guest_name'],$d['branch_id'],$d['res_date'],$d['res_time'],$d['guests'],$d['table_num'],$d['contact'],$d['notes'],$d['status']);
        $stmt->execute();
        respond(['success' => true, 'id' => $db->insert_id]);
    }
}

if ($method === 'DELETE') {
    $id = intval($_GET['id']);
    $db->query("DELETE FROM reservations WHERE id=$id");
    respond(['success' => true]);
}
?>
