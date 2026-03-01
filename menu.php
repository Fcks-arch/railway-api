<?php
require_once 'cors.php';
require_once 'config.php';
// ... rest of your code

require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT m.*, b.name as branch_name FROM menu_items m LEFT JOIN branches b ON m.branch_id=b.id ORDER BY m.category, m.name";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    respond($rows);
}

if ($method === 'POST') {
    $d = getBody();
    if (!empty($d['id'])) {
        $stmt = $db->prepare("UPDATE menu_items SET name=?,category=?,price=?,branch_id=?,description=?,status=? WHERE id=?");
        $bid = !empty($d['branch_id']) ? $d['branch_id'] : null;
        $stmt->bind_param("ssdissi", $d['name'],$d['category'],$d['price'],$bid,$d['description'],$d['status'],$d['id']);
        $stmt->execute();
        respond(['success' => true]);
    } else {
        $stmt = $db->prepare("INSERT INTO menu_items (name,category,price,branch_id,description,status) VALUES (?,?,?,?,?,?)");
        $bid = !empty($d['branch_id']) ? $d['branch_id'] : null;
        $stmt->bind_param("ssdiss", $d['name'],$d['category'],$d['price'],$bid,$d['description'],$d['status']);
        $stmt->execute();
        respond(['success' => true, 'id' => $db->insert_id]);
    }
}

if ($method === 'DELETE') {
    $id = intval($_GET['id']);
    $db->query("DELETE FROM menu_items WHERE id=$id");
    respond(['success' => true]);
}
?>
