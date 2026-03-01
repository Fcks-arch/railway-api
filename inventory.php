<?php
require_once 'cors.php';
require_once 'config.php';
// ... rest of your code

require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT i.*, b.name as branch_name FROM inventory i LEFT JOIN branches b ON i.branch_id=b.id ORDER BY i.name";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    respond($rows);
}

if ($method === 'POST') {
    $d = getBody();
    if (!empty($d['id']) && isset($d['add_stock'])) {
        // Restock
        $stmt = $db->prepare("UPDATE inventory SET stock = stock + ? WHERE id=?");
        $stmt->bind_param("di", $d['add_stock'], $d['id']);
        $stmt->execute();
        respond(['success' => true]);
    } elseif (!empty($d['id'])) {
        $stmt = $db->prepare("UPDATE inventory SET name=?,branch_id=?,stock=?,unit=?,min_level=? WHERE id=?");
        $bid = !empty($d['branch_id']) ? $d['branch_id'] : null;
        $stmt->bind_param("sidddi", $d['name'],$bid,$d['stock'],$d['unit'],$d['min_level'],$d['id']);
        $stmt->execute();
        respond(['success' => true]);
    } else {
        $stmt = $db->prepare("INSERT INTO inventory (name,branch_id,stock,unit,min_level) VALUES (?,?,?,?,?)");
        $bid = !empty($d['branch_id']) ? $d['branch_id'] : null;
        $stmt->bind_param("sidsd", $d['name'],$bid,$d['stock'],$d['unit'],$d['min_level']);
        $stmt->execute();
        respond(['success' => true, 'id' => $db->insert_id]);
    }
}

if ($method === 'DELETE') {
    $id = intval($_GET['id']);
    $db->query("DELETE FROM inventory WHERE id=$id");
    respond(['success' => true]);
}
?>
