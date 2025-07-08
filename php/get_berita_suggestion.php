<?php
require '../config/database.php';

$keyword = $_GET['keyword'] ?? '';

if (empty($keyword)) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, judul FROM berita WHERE judul LIKE :keyword ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([':keyword' => "%$keyword%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode([]);
}
