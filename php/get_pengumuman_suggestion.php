<?php
include '../config/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search === '') {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, judul FROM pengumuman 
        WHERE judul LIKE :search OR deskripsi LIKE :search 
        ORDER BY created_at DESC 
        LIMIT 5";

$stmt = $pdo->prepare($sql);
$stmt->execute([':search' => "%$search%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($results);
?>
