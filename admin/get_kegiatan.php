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
    $stmt = $pdo->prepare("SELECT * FROM kegiatan WHERE id = ?");
    $stmt->execute([$id]);
    $kegiatan = $stmt->fetch();
    
    if ($kegiatan) {
        echo json_encode(['success' => true, 'kegiatan' => $kegiatan]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Kegiatan not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
