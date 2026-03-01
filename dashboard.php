<?php
require_once 'config.php';
$db = getDB();

// Weekly sales per branch
$weekly = [];
$result = $db->query("SELECT s.*, b.name as branch_name, b.color FROM sales s JOIN branches b ON s.branch_id=b.id ORDER BY s.branch_id, s.sale_date DESC LIMIT 28");
while ($row = $result->fetch_assoc()) $weekly[] = $row;

// Branch status
$branches = [];
$result = $db->query("SELECT * FROM branches ORDER BY id");
while ($row = $result->fetch_assoc()) $branches[] = $row;

// Today summary per branch
$summary = [];
$result = $db->query("SELECT s.branch_id, b.name, SUM(s.revenue) as revenue, SUM(s.orders) as orders, SUM(s.customers) as customers FROM sales s JOIN branches b ON s.branch_id=b.id WHERE s.sale_date = CURDATE() GROUP BY s.branch_id");
while ($row = $result->fetch_assoc()) $summary[] = $row;

// Low stock alerts
$alerts = [];
$result = $db->query("SELECT i.name, i.stock, i.min_level, b.name as branch_name FROM inventory i LEFT JOIN branches b ON i.branch_id=b.id WHERE i.stock <= i.min_level ORDER BY i.stock ASC LIMIT 5");
while ($row = $result->fetch_assoc()) $alerts[] = $row;

respond(['weekly' => $weekly, 'branches' => $branches, 'summary' => $summary, 'alerts' => $alerts]);
?>