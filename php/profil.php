<?php 
// Include header dari folder yang sama
include 'Header.php';

// Include database connection dari config
include '../config/database.php';

// Functions to get data and group members
function getPengurusByKategori($pdo, $kategori) {
    $stmt = $pdo->prepare("SELECT * FROM pengurus WHERE kategori = ? ORDER BY 
        CASE 
            WHEN jabatan LIKE '%Ketua%' AND jabatan NOT LIKE '%Wakil%' THEN 1
            WHEN jabatan LIKE '%Wakil%' THEN 2
            WHEN jabatan LIKE '%Sekretaris%' THEN 3
            WHEN jabatan LIKE '%Bendahara%' THEN 4
            WHEN jabatan LIKE '%Koordinator%' THEN 5
            ELSE 6
        END, nama ASC");
    
    $stmt->execute([$kategori]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function groupPengurusByBidangHierarkis($pengurus, $kategori) {
    $grouped = [];
    
    foreach ($pengurus as $anggota) {
        $bidang = $anggota['bidang'];
        $sub_bidang = $anggota['sub_bidang'];
        $jabatan = $anggota['jabatan'];
        
        if ($kategori === 'Majelis Syuro') {
            // Urutan untuk Majelis Syuro
            if (in_array($jabatan, ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'])) {
                $grup = 'BPH';
            } 
            elseif (strpos($bidang, 'Komisi Aspirasi') !== false) {
                $grup = 'Komisi Aspirasi';
            } 
            elseif (strpos($bidang, 'Komisi Legislasi') !== false) {
                $grup = 'Komisi Legislasi';
            } 
            elseif (strpos($bidang, 'Komisi Pengawasan') !== false || strpos($jabatan, 'Pengawas') !== false) {
                $grup = 'Komisi Pengawasan Dan Pengendalian';
            }
            else {
                $grup = !empty($bidang) ? $bidang : 'Lainnya';
            }
            
            // Untuk Majelis Syuro, tidak ada sub-bidang
            $grouped[$grup]['main'][] = $anggota;
        } 
        else {
            // Urutan untuk Senat - HIERARKIS DENGAN SUB-BIDANG
            if (in_array($jabatan, ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'])) {
                $grup = 'BPH';
                $grouped[$grup]['main'][] = $anggota;
            }
            // Bidang Humas - dengan subbidang TIK dan Kerjasama
            elseif (strpos($jabatan, 'Koordinator Bidang Hubungan Masyarakat') !== false ||
                    strpos($jabatan, 'Koordinator Bidang Humas') !== false ||
                    (strpos($bidang, 'Hubungan Masyarakat') !== false && empty($sub_bidang))) {
                $grup = 'Hubungan Masyarakat';
                $grouped[$grup]['main'][] = $anggota;
            }
            elseif (strpos($bidang, 'Hubungan Masyarakat') !== false && $sub_bidang === 'TIK') {
                $grup = 'Hubungan Masyarakat';
                $grouped[$grup]['sub']['TIK'][] = $anggota;
            }
            elseif (strpos($bidang, 'Hubungan Masyarakat') !== false && $sub_bidang === 'Kerjasama') {
                $grup = 'Hubungan Masyarakat';
                $grouped[$grup]['sub']['Kerjasama'][] = $anggota;
            }
            // Bidang Pelayanan Umat
            elseif (strpos($jabatan, 'Koordinator Bidang Pelayanan Umat') !== false ||
                    strpos($bidang, 'Pelayanan Umat') !== false) {
                $grup = 'Pelayanan Umat';
                $grouped[$grup]['main'][] = $anggota;
            }
            // Bidang Tarbiyah - dengan subbidang Syiar dan Kaderisasi
            elseif (strpos($jabatan, 'Koordinator Bidang Tarbiyah') !== false ||
                    (strpos($bidang, 'Tarbiyah') !== false && empty($sub_bidang))) {
                $grup = 'Tarbiyah';
                $grouped[$grup]['main'][] = $anggota;
            }
            elseif (strpos($bidang, 'Tarbiyah') !== false && $sub_bidang === 'Syiar') {
                $grup = 'Tarbiyah';
                $grouped[$grup]['sub']['Syiar'][] = $anggota;
            }
            elseif (strpos($bidang, 'Tarbiyah') !== false && $sub_bidang === 'Kaderisasi') {
                $grup = 'Tarbiyah';
                $grouped[$grup]['sub']['Kaderisasi'][] = $anggota;
            }
            // Bidang Kemuslimahan
            elseif (strpos($jabatan, 'Koordinator Bidang Kemuslimahan') !== false ||
                    strpos($bidang, 'Kemuslimahan') !== false) {
                $grup = 'Kemuslimahan';
                $grouped[$grup]['main'][] = $anggota;
            }
            else {
                // Debug: tampilkan data yang tidak terdeteksi
                echo "<!-- TIDAK TERDETEKSI: Jabatan='" . $jabatan . "', Bidang='" . $bidang . "', Sub_bidang='" . $sub_bidang . "' -->";
                $grup = !empty($bidang) ? $bidang : 'Lainnya';
                $grouped[$grup]['main'][] = $anggota;
            }
        }
    }
    
    return $grouped;
}

function getAvatarClass($jabatan) {
    if (strpos($jabatan, 'Ketua') !== false && strpos($jabatan, 'Wakil') === false) {
        return 'ketua';
    } elseif (strpos($jabatan, 'Wakil') !== false) {
        return 'wakil';
    } elseif (strpos($jabatan, 'Sekretaris') !== false) {
        return 'sekretaris';
    } elseif (strpos($jabatan, 'Bendahara') !== false) {
        return 'bendahara';
    } elseif (strpos($jabatan, 'Koordinator') !== false) {
        return 'koordinator';
    } elseif (strpos($jabatan, 'Pengawas') !== false) {
        return 'koordinator';
    } else {
        return 'komisi';
    }
}

function getAvatarIcon($jabatan) {
    if (strpos($jabatan, 'Bendahara') !== false) {
        return 'fas fa-coins';
    } elseif (strpos($jabatan, 'Koordinator') !== false) {
        return 'fas fa-user-tie';
    } elseif (strpos($jabatan, 'Pengawas') !== false) {
        return 'fas fa-eye';
    } elseif (strpos($jabatan, 'Ketua') !== false && strpos($jabatan, 'Wakil') === false) {
        return 'fas fa-crown';
    } elseif (strpos($jabatan, 'Wakil') !== false) {
        return 'fas fa-shield-alt';
    } elseif (strpos($jabatan, 'Sekretaris') !== false) {
        return 'fas fa-file-alt';
    } else {
        return 'fas fa-user';
    }
}

// Get data from database
$majelisSyuro = getPengurusByKategori($pdo, 'Majelis Syuro');
$senat = getPengurusByKategori($pdo, 'Senat');

// Group by bidang dengan struktur hierarkis
$groupedMajelis = groupPengurusByBidangHierarkis($majelisSyuro, 'Majelis Syuro');
$groupedSenat = groupPengurusByBidangHierarkis($senat, 'Senat');

// Debug output
echo "<!-- FINAL DEBUG INFO -->";
echo "<!-- Majelis Syuro count: " . count($majelisSyuro) . " -->";
echo "<!-- Senat count: " . count($senat) . " -->";
?>

<!-- Link CSS untuk halaman profil (keluar dari folder php) -->
<link rel="stylesheet" href="../css/profil.css">

<main class="main-content">
    <section class="page-header">
        <div class="container">
            <h1>Profil Organisasi</h1>
            <p>Mengenal lebih dekat Forum Kerjasama Rohani Islam PTK Indonesia</p>
        </div>
    </section>

    <section class="sejarah-section">
        <div class="container">
            <div class="content-card">
                <h2>Sejarah Singkat</h2>
                <p>FOKRI PTK terbentuk melalui proses panjang karena adanya kebutuhan Aktivis Da'wah Kampus (ADK) di Perguruan Tinggi Kedinasan (PTK) untuk menyelaraskan langkah dalam membina dan memperbarui budaya kampus menuju lingkungan yang islami.</p>
                
                <div class="faktor-section">
                    <h3>Tiga faktor utama yang mendorong pembentukan forum ini adalah:</h3>
                    <div class="faktor-grid">
                        <div class="faktor-item">
                            <div class="faktor-icon"><i class="fas fa-graduation-cap"></i></div>
                            <h4>Kesamaan Ikatan Dinas</h4>
                            <p>PTK memiliki sistem pendidikan dengan aturan yang lebih ketat dibanding perguruan tinggi lain.</p>
                        </div>
                        <div class="faktor-item">
                            <div class="faktor-icon"><i class="fas fa-users"></i></div>
                            <h4>Mental Senioritas</h4>
                            <p>Tradisi senioritas yang kuat di PTK dapat dimanfaatkan untuk pembinaan positif dalam dakwah.</p>
                        </div>
                        <div class="faktor-item">
                            <div class="faktor-icon"><i class="fas fa-landmark"></i></div>
                            <h4>Posisi Strategis Alumni</h4>
                            <p>Lulusan PTK berperan penting dalam pemerintahan, sehingga perlu pembentukan kader yang berkomitmen pada amanah umat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="timeline-section">
        <div class="container">
            <h2>Perjalanan Sejarah & Dinamika Perkembangan</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-year">1997</div>
                    <div class="timeline-content">
                        <h3>Awal Mula</h3>
                        <p>Kerjasama ROHIS antar PTK dimulai dengan pembentukan Forum Mahasiswa Kedinasan Se-Jabotabek (FORMASI Se-JABOTABEK) oleh STIS, STAN, Poltek GT, dan STPI</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">1998</div>
                    <div class="timeline-content">
                        <h3>Perubahan Nama</h3>
                        <p>Pada Ahad, 5 Juli 1998, nama forum diubah menjadi Forum Komunikasi Sekolah Kedinasan Se - JABOTABEK (FORSINAS Se-JABOTABEK) dalam Rapat Akbar yang dihadiri oleh 10 PTK.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2001</div>
                    <div class="timeline-content">
                        <h3>FOKRI PTK Terbentuk</h3>
                        <p>Dalam Rapat Akbar tanggal 29-30 April, forum ini berganti nama menjadi Forum Kerjasama Rohani Islam Perguruan Tinggi Kedinasan (FOKRI PTK) dan meluaskan cakupan dakwah ke tingkat nasional.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">1 Juli 2001</div>
                    <div class="timeline-content">
                        <h3>Ekspansi Nasional</h3>
                        <p>STPN Yogyakarta, AKSARA Bogor, dan AIM Jakarta resmi bergabung dengan FOKRI PTK, memperkuat jangkauan organisasi dalam membina kader dakwah di lingkungan PTK.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="visi-misi-section">
        <div class="container">
            <div class="visi-misi-grid">
                <div class="visi-card">
                    <h2>Visi</h2>
                    <p>Visi dari organisasi FOKRI PTK adalah menjadi wadah organisasi keislaman yang mandiri, kontributif, bersahabat dan aspiratif bagi kesejahteraan umat.</p>
                </div>
                <div class="misi-card">
                    <h2>Misi</h2>
                    <ul>
                        <li>Turut berkontribusi dalam dunia dakwah kampus dengan melakukan pembinaan dan pengembangan terhadap organisasi keislaman perguruan tinggi kedinasan.</li>
                        <li>Melahirkan generasi yang cerdas, kuat, dan sehat secara jasmani maupun rohani.</li>
                        <li>Meningkatkan ukhuwah islamiyah antar perguruan tinggi kedinasan di Indonesia.</li>
                        <li>Memperluas jaringan kerja sama antar perguruan tinggi kedinasan dan elemen organisasi lainnya.</li>
                        <li>Memberikan kebijakan dan menampung aspirasi organisasi keislaman perguruan tinggi kedinasan dalam menyelenggarakan dakwah di perguruan tinggi kedinasan anggota Forum Kerja Sama Rohani Islam Perguruan Tinggi Kedinasan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Structure Section -->
    <section class="struktur-section">
        <div class="container">
            <h2>Struktur Kepengurusan</h2>
            
            <div class="struktur-tabs">
                <button class="tab-btn active" data-tab="majelis" id="btn-majelis">Majelis Syuro</button>
                <button class="tab-btn" data-tab="senat" id="btn-senat">Senat</button>
            </div>
            
            <!-- Tab Majelis Syuro -->
            <div class="tab-content active" id="majelis" data-tab-type="majelis">
                <div class="struktur-section-title">
                    <h3>Majelis Syuro FOKRI PTK Indonesia Periode 2024/2025</h3>
                </div>
                
                <?php if (!empty($groupedMajelis)): ?>
                    <?php foreach ($groupedMajelis as $grupNama => $grupData): ?>
                    <div class="main-group-section">
                        <div class="main-group-title">
                            <?php echo htmlspecialchars($grupNama); ?>
                        </div>
                        
                        <?php if (!empty($grupData['main'])): ?>
                        <div class="member-grid">
                            <?php foreach ($grupData['main'] as $anggota): ?>
                            <div class="member-card" data-kategori="majelis">
                                <div class="member-avatar <?php echo getAvatarClass($anggota['jabatan']); ?>">
                                    <i class="<?php echo getAvatarIcon($anggota['jabatan']); ?>"></i>
                                </div>
                                <h5><?php echo htmlspecialchars($anggota['nama']); ?></h5>
                                <p class="position"><?php echo htmlspecialchars($anggota['jabatan']); ?></p>
                                <p class="institution"><?php echo htmlspecialchars($anggota['asal_ptk']); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-users fa-3x"></i>
                        <p>Data Majelis Syuro belum tersedia.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Tab Senat -->
            <div class="tab-content" id="senat" data-tab-type="senat">
                <div class="struktur-section-title">
                    <h3>Senat FOKRI PTK Indonesia Periode 2024/2025</h3>
                </div>
                
                <?php if (!empty($groupedSenat)): ?>
                    <?php foreach ($groupedSenat as $grupNama => $grupData): ?>
                    <div class="main-group-section">
                        <div class="main-group-title">
                            <?php echo htmlspecialchars($grupNama); ?>
                        </div>
                        
                        <!-- Anggota Bidang Utama -->
                        <?php if (!empty($grupData['main'])): ?>
                        <div class="member-grid">
                            <?php foreach ($grupData['main'] as $anggota): ?>
                            <div class="member-card" data-kategori="senat">
                                <div class="member-avatar <?php echo getAvatarClass($anggota['jabatan']); ?>">
                                    <i class="<?php echo getAvatarIcon($anggota['jabatan']); ?>"></i>
                                </div>
                                <h5><?php echo htmlspecialchars($anggota['nama']); ?></h5>
                                <p class="position"><?php echo htmlspecialchars($anggota['jabatan']); ?></p>
                                <p class="institution"><?php echo htmlspecialchars($anggota['asal_ptk']); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Sub-bidang -->
                        <?php if (!empty($grupData['sub'])): ?>
                            <?php foreach ($grupData['sub'] as $subNama => $subAnggota): ?>
                            <div class="sub-position">
                                <div class="sub-group-title">
                                    Sub-bidang <?php echo htmlspecialchars($subNama); ?>
                                </div>
                                <div class="member-grid sub-grid">
                                    <?php foreach ($subAnggota as $anggota): ?>
                                    <div class="member-card sub-member" data-kategori="senat">
                                        <div class="member-avatar <?php echo getAvatarClass($anggota['jabatan']); ?>">
                                            <i class="<?php echo getAvatarIcon($anggota['jabatan']); ?>"></i>
                                        </div>
                                        <h5><?php echo htmlspecialchars($anggota['nama']); ?></h5>
                                        <p class="position"><?php echo htmlspecialchars($anggota['jabatan']); ?></p>
                                        <p class="institution"><?php echo htmlspecialchars($anggota['asal_ptk']); ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-university fa-3x"></i>
                        <p>Data Senat belum tersedia.</p>
                        <p><small>Jumlah data Senat yang ditemukan: <?php echo count($senat); ?></small></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<!-- Link JavaScript untuk halaman profil (keluar dari folder php) -->
<script src="../js/profil.js"></script>

<?php 
// Include footer dari folder yang sama
include 'footer.php'; 
?>
