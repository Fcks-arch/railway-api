<?php
require_once 'cors.php';
require_once 'config.php';
// ... rest of your code

// Restaurant API - Entry point
header('Content-Type: application/json');
echo json_encode(['status' => 'Restaurant API is running!', 'version' => '1.0']);
?>
