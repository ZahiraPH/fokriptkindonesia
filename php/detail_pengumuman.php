<?php 
include 'header.php';
include '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: pengumuman.php');
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT * FROM pengumuman WHERE id = ?");
    $stmt->execute([$id]);
    $pengumuman = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pengumuman) {
        header('Location: pengumuman.php');
        exit();
    }
} catch (PDOException $e) {
    header('Location: pengumuman.php');
    exit();
}
?>

<link rel="stylesheet" href="../css/detail.css">

<main class="main-content">
    <section class="detail-header">
        <div class="container">
            <nav class="breadcrumb">
                <a href="beranda.php">Beranda</a>
                <span class="separator">></span>
                <a href="pengumuman.php">Pengumuman</a>
                <span class="separator">></span>
                <span class="current"><?php echo htmlspecialchars($pengumuman['judul']); ?></span>
            </nav>
        </div>
    </section>

    <section class="detail-content">
        <div class="container">
            <article class="detail-article">
                <header class="article-header">
                    <h1><?php echo htmlspecialchars($pengumuman['judul']); ?></h1>

                    <div class="status-container">
                        <div class="status-badge <?php echo $pengumuman['status']; ?>">
                            <?php echo $pengumuman['status'] === 'active' ? 'Aktif' : 'Tidak Aktif'; ?>
                        </div>
                    </div>

                    <div class="article-meta">
                        <span class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <?php echo date('d M Y', strtotime($pengumuman['created_at'])); ?>
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            <?php echo date('H:i', strtotime($pengumuman['created_at'])); ?>
                        </span>
                    </div>
                </header>


                <div class="article-body">
                    <?php echo nl2br(htmlspecialchars($pengumuman['deskripsi'])); ?>
                </div>

                <div class="article-actions">
                    <a href="pengumuman.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Pengumuman
                    </a>
                </div>
            </article>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
