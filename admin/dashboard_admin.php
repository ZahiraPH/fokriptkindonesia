<?php
session_start();
include '../config/database.php';

$page_title = 'Dashboard Admin';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Get statistics
try {
    // Count berita
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM berita");
    $total_berita = $stmt->fetch()['total'];
    
    // Count kegiatan
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM kegiatan");
    $total_kegiatan = $stmt->fetch()['total'];
    
    // Count dokumen
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM dokumen");
    $total_dokumen = $stmt->fetch()['total'];
    
    // Count pengumuman
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM pengumuman");
    $total_pengumuman = $stmt->fetch()['total'];
    
    // Count pengurus
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM pengurus");
    $total_pengurus = $stmt->fetch()['total'];
    
    // Get recent activities
    $recent_berita = $pdo->query("SELECT judul, created_at FROM berita ORDER BY created_at DESC LIMIT 5")->fetchAll();
    $recent_kegiatan = $pdo->query("SELECT judul, tanggal_mulai FROM kegiatan ORDER BY created_at DESC LIMIT 5")->fetchAll();
    $upcoming_kegiatan = $pdo->query("SELECT judul, tanggal_mulai FROM kegiatan WHERE status = 'upcoming' ORDER BY tanggal_mulai ASC LIMIT 5")->fetchAll();
    
} catch (PDOException $e) {
    $error = 'Terjadi kesalahan dalam mengambil data';
}

include 'admin_header.php';
?>

<link rel="stylesheet" href="../css/dashboard_admin.css">

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_berita; ?></h3>
                <p>Total Berita</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_kegiatan; ?></h3>
                <p>Total Kegiatan</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_dokumen; ?></h3>
                <p>Total Dokumen</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_pengumuman; ?></h3>
                <p>Total Pengumuman</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_pengurus; ?></h3>
                <p>Total Pengurus</p>
            </div>
        </div>
    </div>
    
    <div class="activity-grid">
        <div class="activity-card">
            <div class="card-header">
                <h3><i class="fas fa-newspaper"></i> Berita Terbaru</h3>
                <a href="kelola_berita.php" class="view-all">Lihat Semua</a>
            </div>
            <div class="card-content">
                <?php if (!empty($recent_berita)): ?>
                    <ul class="activity-list">
                        <?php foreach ($recent_berita as $berita): ?>
                            <li>
                                <span class="activity-title"><?php echo htmlspecialchars($berita['judul']); ?></span>
                                <span class="activity-date"><?php echo date('d M Y', strtotime($berita['created_at'])); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="no-data">Belum ada berita</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="activity-card">
            <div class="card-header">
                <h3><i class="fas fa-calendar-check"></i> Kegiatan Mendatang</h3>
                <a href="kelola_kegiatan.php" class="view-all">Lihat Semua</a>
            </div>
            <div class="card-content">
                <?php if (!empty($upcoming_kegiatan)): ?>
                    <ul class="activity-list">
                        <?php foreach ($upcoming_kegiatan as $kegiatan): ?>
                            <li>
                                <span class="activity-title"><?php echo htmlspecialchars($kegiatan['judul']); ?></span>
                                <span class="activity-date"><?php echo date('d M Y', strtotime($kegiatan['tanggal_mulai'])); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="no-data">Belum ada kegiatan mendatang</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="activity-card">
            <div class="card-header">
                <h3><i class="fas fa-clock"></i> Aktivitas Terbaru</h3>
            </div>
            <div class="card-content">
                <?php if (!empty($recent_kegiatan)): ?>
                    <ul class="activity-list">
                        <?php foreach ($recent_kegiatan as $kegiatan): ?>
                            <li>
                                <span class="activity-title"><?php echo htmlspecialchars($kegiatan['judul']); ?></span>
                                <span class="activity-date"><?php echo date('d M Y', strtotime($kegiatan['tanggal_mulai'])); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="no-data">Belum ada aktivitas</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
