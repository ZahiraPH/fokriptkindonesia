<?php
session_start();
include '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID required']);
    exit();
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM pengurus WHERE id = ?");
    $stmt->execute([$id]);
    $pengurus = $stmt->fetch();
    
    if ($pengurus) {
        // Don't return password for security
        unset($pengurus['password']);
        echo json_encode(['success' => true, 'pengurus' => $pengurus]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Pengurus not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
