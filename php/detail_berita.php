<?php 
include 'header.php';
include '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: berita.php');
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT berita.*, pengurus.nama, pengurus.bidang FROM berita LEFT JOIN pengurus ON berita.penulis = pengurus.id WHERE berita.id = ?");

    $stmt->execute([$id]);
    $berita = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$berita) {
        header('Location: berita.php');
        exit();
    }
} catch (PDOException $e) {
    header('Location: berita.php');
    exit();
}
?>

<link rel="stylesheet" href="../css/detail.css">

<main class="main-content">
    <section class="detail-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="beranda.php">Beranda</a>
                <span class="separator">></span>
                <a href="berita.php">Berita</a>
                <span class="separator">></span>
                <span class="current"><?php echo htmlspecialchars($berita['judul']); ?></span>
            </div>
        </div>
    </section>

    <section class="detail-content">
        <div class="container">
            <article class="detail-article">
                <header class="article-header">
                    <h1><?php echo htmlspecialchars($berita['judul']); ?></h1>
                    <div class="status-container">
                        <div class="status-badge finished">Dipublikasikan</div>
                    </div>
                </header>

                <div class="kegiatan-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="info-content">
                                <h4>Tanggal Publikasi</h4>
                                <p><?php echo date('d M Y', strtotime($berita['created_at'])); ?></p>
                            </div>
                        </div>

                        <?php if (!empty($berita['penulis'])): ?>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="info-content">
                                <h4>Penulis</h4>
                                <p>
                                    <?php 
                                        if (!empty($berita['nama']) && !empty($berita['bidang'])) {
                                            echo htmlspecialchars($berita['nama'] . ' (' . $berita['bidang'] . ')');
                                        } elseif (!empty($berita['nama'])) {
                                            echo htmlspecialchars($berita['nama']);
                                        } else {
                                            echo 'Admin';
                                        }
                                    ?>
                                </p>

                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($berita['gambar'])): ?>
                <div class="article-image">
                    <img src="../uploads/berita/<?php echo htmlspecialchars($berita['gambar']); ?>" alt="Gambar Berita">
                </div>
                <?php endif; ?>

                <div class="article-body">
                    <h3>Isi Berita</h3>
                    <?php echo nl2br(htmlspecialchars($berita['deskripsi'])); ?>
                </div>

                <div class="article-actions">
                    <a href="berita.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Berita
                    </a>
                </div>
            </article>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
