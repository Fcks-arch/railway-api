<?php
require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT e.*, b.name as branch_name FROM events e LEFT JOIN branches b ON e.branch_id=b.id ORDER BY e.event_date ASC";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    respond($rows);
}

if ($method === 'POST') {
    $d = getBody();
    if (!empty($d['id'])) {
        $stmt = $db->prepare("UPDATE events SET status=? WHERE id=?");
        $stmt->bind_param("si", $d['status'], $d['id']);
        $stmt->execute();
        respond(['success' => true]);
    } else {
        $stmt = $db->prepare("INSERT INTO events (event_name,event_type,branch_id,event_date,event_time,guests,package,deposit,status) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssississs", $d['event_name'],$d['event_type'],$d['branch_id'],$d['event_date'],$d['event_time'],$d['guests'],$d['package'],$d['deposit'],$d['status']);
        $stmt->execute();
        respond(['success' => true, 'id' => $db->insert_id]);
    }
}

if ($method === 'DELETE') {
    $id = intval($_GET['id']);
    $db->query("DELETE FROM events WHERE id=$id");
    respond(['success' => true]);
}
?>