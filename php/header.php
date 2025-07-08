<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOKRI Connect - Forum Kerjasama Rohani Islam PTK</title>
    <link rel="stylesheet" href="../css/Header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo-section">
                <img src="../aset/logo-majelis.png" alt="Logo Majelis" class="logoMajelis">
                <img src="../aset/logo-fokri.png" alt="Logo FOKRI" class="logoFokri">
                <div class="site-title">
                    <h1>FOKRI Connect</h1>
                    <p>Forum Kerjasama Rohani Islam PTK Indonesia</p>
                </div>
            </div>
            
            <nav class="main-nav">
                <ul class="nav-list">
                    <li><a href="Beranda.php" class="nav-link">Beranda</a></li>
                    <li><a href="Profil.php" class="nav-link">Profil</a></li>
                    <li class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle">
                            Informasi <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="Berita.php">Berita</a></li>
                            <li><a href="Kegiatan.php">Kegiatan</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle">
                            Publikasi <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="Dokumen.php">Dokumen</a></li>
                            <li><a href="Pengumuman.php">Pengumuman</a></li>
                        </ul>
                    </li>
                    <li><a href="Login.php" class="nav-link login-btn">Login</a></li>
                </ul>
            </nav>
            
            <div class="mobile-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>
    
    <div class="mobile-nav">
        <ul class="mobile-nav-list">
            <li><a href="Beranda.php">Beranda</a></li>
            <li><a href="Profil.php">Profil</a></li>
            <li>
                <a href="#" class="mobile-dropdown-toggle">Informasi <i class="fas fa-chevron-down"></i></a>
                <ul class="mobile-dropdown">
                    <li><a href="Berita.php">Berita</a></li>
                    <li><a href="Kegiatan.php">Kegiatan</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="mobile-dropdown-toggle">Publikasi <i class="fas fa-chevron-down"></i></a>
                <ul class="mobile-dropdown">
                    <li><a href="Dokumen.php">Dokumen</a></li>
                    <li><a href="Pengumuman.php">Pengumuman</a></li>
                </ul>
            </li>
            <li><a href="Login.php">Login</a></li>
        </ul>
    </div>
    
    <script src="../js/Header.js"></script>
