<?php 
include 'header.php';
include '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: kegiatan.php');
    exit();
}

try {
    $stmt = $pdo->prepare("
    SELECT kegiatan.*, pengurus.nama AS nama_pj
    FROM kegiatan
    LEFT JOIN pengurus ON kegiatan.penanggung_jawab = pengurus.id
    WHERE kegiatan.id = ?
");

    $stmt->execute([$id]);
    $kegiatan = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$kegiatan) {
        header('Location: kegiatan.php');
        exit();
    }
} catch (PDOException $e) {
    header('Location: kegiatan.php');
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
                <a href="kegiatan.php">Kegiatan</a>
                <span class="separator">></span>
                <span class="current"><?php echo htmlspecialchars($kegiatan['judul']); ?></span>
            </nav>
        </div>
    </section>

    <section class="detail-content">
        <div class="container">
            <article class="detail-article">
                <header class="article-header">
                    <h1><?php echo htmlspecialchars($kegiatan['judul']); ?></h1>
                    <div class="status-badge <?php echo $kegiatan['status']; ?>">
                        <?php echo $kegiatan['status'] === 'upcoming' ? 'Akan Datang' : 'Selesai'; ?>
                    </div>
                </header>

                <div class="kegiatan-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="info-content">
                                <h4>Tanggal Mulai</h4>
                                <p><?php echo date('d M Y', strtotime($kegiatan['tanggal_mulai'])); ?></p>
                            </div>
                        </div>

                        <?php if ($kegiatan['tanggal_selesai']): ?>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Tanggal Selesai</h4>
                                    <p><?php echo date('d M Y', strtotime($kegiatan['tanggal_selesai'])); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($kegiatan['lokasi']): ?>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Lokasi</h4>
                                    <p><?php echo htmlspecialchars($kegiatan['lokasi']); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($kegiatan['penanggung_jawab']): ?>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Penanggung Jawab</h4>
                                    <p><?php echo htmlspecialchars($kegiatan['nama_pj']); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($kegiatan['bidang']): ?>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Bidang</h4>
                                    <p><?php echo htmlspecialchars($kegiatan['bidang']); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="article-body">
                    <h3>Deskripsi Kegiatan</h3>
                    <?php echo nl2br(htmlspecialchars($kegiatan['deskripsi'])); ?>
                </div>

                <div class="article-actions">
                    <a href="kegiatan.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Kegiatan
                    </a>
                </div>
            </article>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
