<?php
include '../config/database.php';

// Ambil keyword dari query string
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// Jika keyword tidak kosong, lakukan pencarian
if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT id, judul FROM kegiatan WHERE judul LIKE :keyword ORDER BY tanggal_mulai DESC LIMIT 10");
    $stmt->execute([':keyword' => "%$keyword%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Kembalikan hasil dalam bentuk JSON
    echo json_encode($results);
} else {
    // Jika kosong, kembalikan array kosong
    echo json_encode([]);
}
