<?php
include '../config/database.php'; // Sesuaikan path koneksi

header('Content-Type: application/json');

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$result = [];

if ($keyword !== '') {
    try {
        $stmt = $pdo->prepare("SELECT judul FROM dokumen WHERE judul LIKE :keyword ORDER BY judul ASC LIMIT 10");
        $stmt->execute([':keyword' => '%' . $keyword . '%']);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $result = [];
    }
}

echo json_encode($result);
?>
