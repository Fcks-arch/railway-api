<?php
require_once 'cors.php';
require_once 'config.php';
// ... rest of your code

require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT s.*, b.name as branch_name FROM staff s LEFT JOIN branches b ON s.branch_id=b.id ORDER BY b.name, s.name";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    respond($rows);
}

if ($method === 'POST') {
    $d = getBody();
    if (!empty($d['id'])) {
        $stmt = $db->prepare("UPDATE staff SET name=?,role=?,branch_id=?,schedule=?,status=?,contact=? WHERE id=?");
        $stmt->bind_param("ssisssi", $d['name'],$d['role'],$d['branch_id'],$d['schedule'],$d['status'],$d['contact'],$d['id']);
        $stmt->execute();
        respond(['success' => true]);
    } else {
        $stmt = $db->prepare("INSERT INTO staff (name,role,branch_id,schedule,status,contact) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssisss", $d['name'],$d['role'],$d['branch_id'],$d['schedule'],$d['status'],$d['contact']);
        $stmt->execute();
        respond(['success' => true, 'id' => $db->insert_id]);
    }
}

if ($method === 'DELETE') {
    $id = intval($_GET['id']);
    $db->query("DELETE FROM staff WHERE id=$id");
    respond(['success' => true]);
}
?>
