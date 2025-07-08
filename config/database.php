<?php
$host = 'localhost';
$dbname = 'projec15_fokri_ptk';
$username = 'projec15_root';
$password = '@kaesquare123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
