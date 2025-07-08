<?php
include '../config/database.php'; // Pastikan path sesuai struktur proyekmu

// Ambil keyword dari parameter GET
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// Siapkan array kosong untuk hasil
$results = [];

try {
    if ($keyword !== '') {
        // Query jika ada keyword (pencarian)
        $stmt = $pdo->prepare("SELECT * FROM dokumen WHERE judul LIKE :keyword ORDER BY tanggal_upload DESC");
        $stmt->execute(['keyword' => "%$keyword%"]);
    } else {
        // Query default (semua data)
        $stmt = $pdo->query("SELECT * FROM dokumen ORDER BY tanggal_upload DESC");
    }

    // Ambil semua hasil
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Kirim data sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($results);
} catch (PDOException $e) {
    // Kirim error jika gagal
    echo json_encode(['error' => $e->getMessage()]);
}
