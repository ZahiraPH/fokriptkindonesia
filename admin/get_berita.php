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
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
    $stmt->execute([$id]);
    $berita = $stmt->fetch();
    
    if ($berita) {
        echo json_encode([
        'success' => true,
        'berita' => [
            'id' => $berita['id'],
            'judul' => $berita['judul'],
            'deskripsi' => $berita['deskripsi'],
            'gambar' => $berita['gambar'],
            'penulis' => $berita['penulis'],
            'created_at' => $berita['created_at']
        ]
    ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Berita not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
