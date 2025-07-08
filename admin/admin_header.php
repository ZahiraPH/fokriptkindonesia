<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Panel'; ?> - FOKRI Connect</title>
    <link rel="stylesheet" href="../css/admin_header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="admin-navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <img src="../aset/logo-fokri.png" alt="FOKRI Logo" class="navbar-logo">
                <div class="brand-text">
                    <h2>FOKRI Connect</h2>
                    <span>Admin Panel</span>
                </div>
            </div>
            
            <div class="navbar-menu">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="dashboard_admin.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard_admin.php' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_berita.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kelola_berita.php' ? 'active' : ''; ?>">
                            <i class="fas fa-newspaper"></i>
                            <span>Kelola Berita</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_kegiatan.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kelola_kegiatan.php' ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Kelola Kegiatan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_dokumen.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kelola_dokumen.php' ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt"></i>
                            <span>Kelola Dokumen</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_pengumuman.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kelola_pengumuman.php' ? 'active' : ''; ?>">
                            <i class="fas fa-bullhorn"></i>
                            <span>Kelola Pengumuman</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_pengurus.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kelola_pengurus.php' ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i>
                            <span>Kelola Pengurus</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="navbar-user">
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                    <span class="user-role"><?php echo htmlspecialchars($_SESSION['kategori']); ?></span>
                </div>
                <div class="user-actions">
                    <a href="lihat_website.php" class="nav-link" title="Lihat Website">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <a href="logout.php" class="nav-link logout-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
            
            <div class="mobile-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>
    
    <div class="mobile-menu">
        <ul class="mobile-nav">
            <li><a href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="kelola_berita.php"><i class="fas fa-newspaper"></i> Kelola Berita</a></li>
            <li><a href="kelola_kegiatan.php"><i class="fas fa-calendar-alt"></i> Kelola Kegiatan</a></li>
            <li><a href="kelola_dokumen.php"><i class="fas fa-file-alt"></i> Kelola Dokumen</a></li>
            <li><a href="kelola_pengumuman.php"><i class="fas fa-bullhorn"></i> Kelola Pengumuman</a></li>
            <li><a href="kelola_pengurus.php"><i class="fas fa-users"></i> Kelola Pengurus</a></li>
            <li><a href="lihat_website.php"><i class="fas fa-external-link-alt"></i> Lihat Website</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    
    <script src="../js/admin_header.js"></script>
