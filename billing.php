<?php
require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT bi.*, b.name as branch_name FROM billing bi LEFT JOIN branches b ON bi.branch_id=b.id ORDER BY bi.created_at DESC";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    respond($rows);
}

if ($method === 'POST') {
    $d = getBody();
    if (!empty($d['id'])) {
        $stmt = $db->prepare("UPDATE billing SET status=? WHERE id=?");
        $stmt->bind_param("si", $d['status'], $d['id']);
        $stmt->execute();
        respond(['success' => true]);
    } else {
        $num = 'B-' . strval(1000 + rand(1,9999));
        $stmt = $db->prepare("INSERT INTO billing (bill_number,branch_id,table_num,items,amount,payment_method,status) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("sissds", $num,$d['branch_id'],$d['table_num'],$d['items'],$d['amount'],$d['payment_method'],$d['status']);
        $stmt->execute();
        respond(['success' => true, 'id' => $db->insert_id]);
    }
}
?>