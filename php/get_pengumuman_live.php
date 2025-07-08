<?php
include '../config/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$conditions = [];
$params = [];

if ($search !== '') {
    $conditions[] = "(judul LIKE :search OR deskripsi LIKE :search)";
    $params[':search'] = "%$search%";
}

if ($status !== '') {
    $conditions[] = "status = :status";
    $params[':status'] = $status;
}

$where = '';
if (!empty($conditions)) {
    $where = 'WHERE ' . implode(' AND ', $conditions);
}

$sql = "SELECT * FROM pengumuman 
        $where 
        ORDER BY created_at DESC 
        LIMIT 20";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($data);
?>
