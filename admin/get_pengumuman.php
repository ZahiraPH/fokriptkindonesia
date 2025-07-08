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
    $stmt = $pdo->prepare("
    SELECT p.*, g.nama AS nama_pengurus 
    FROM pengumuman p 
    LEFT JOIN pengurus g ON p.created_by = g.id 
    WHERE p.id = ?
    ");
    $stmt->execute([$id]);
    $pengumuman = $stmt->fetch();
    
if ($pengumuman) {
    echo json_encode([
        'success' => true,
        'pengumuman' => [
            'id' => $pengumuman['id'],
            'judul' => $pengumuman['judul'],
            'deskripsi' => $pengumuman['deskripsi'],
            'status' => $pengumuman['status'],
            'created_at' => $pengumuman['created_at'],
            'created_by' => $pengumuman['created_by'], // penting untuk edit
            'nama_pengurus' => $pengumuman['nama_pengurus'] ?? ''
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Pengumuman not found']);
}

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
