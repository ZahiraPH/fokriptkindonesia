<?php 
include 'Header.php';
include '../config/database.php';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Search and filter functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

$searchCondition = '';
$params = [];

if (!empty($search)) {
    $searchCondition .= " AND (judul LIKE :search OR deskripsi LIKE :search OR lokasi LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($status)) {
    $searchCondition .= " AND status = :status";
    $params[':status'] = $status;
}

// Get total count
$countSql = "SELECT COUNT(*) FROM kegiatan WHERE 1=1 $searchCondition";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get kegiatan data
$sql = "SELECT kegiatan.*, pengurus.nama 
        FROM kegiatan 
        LEFT JOIN pengurus ON kegiatan.penanggung_jawab = pengurus.id 
        WHERE 1=1 $searchCondition 
        ORDER BY kegiatan.tanggal_mulai DESC 
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$kegiatan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../css/kegiatan.css">

<main class="main-content">
    <section class="page-header">
        <div class="container">
            <h1>Kegiatan FOKRI PTK</h1>
            <p>Daftar kegiatan dan program kerja FOKRI PTK</p>
        </div>
    </section>

    <section class="kegiatan-section">
        <div class="container">
            <div class="filter-section">
                <form action="kegiatan.php" method="GET" class="filter-form search-form">
                    <div class="search-group">
                        <div class="search-input-group">
                            <input type="text" name="search" id="searchInput" placeholder="Cari kegiatan..." value="<?php echo htmlspecialchars($search); ?>" class="search-input">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <select name="status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="upcoming" <?php echo $status === 'upcoming' ? 'selected' : ''; ?>>Akan Datang</option>
                            <option value="finished" <?php echo $status === 'finished' ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                        <button type="submit" class="filter-btn">Filter</button>
                    </div>
                </form>
            </div>

            <?php if (empty($kegiatan)): ?>
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Tidak ada kegiatan ditemukan</h3>
                    <p><?php echo !empty($search) || !empty($status) ? 'Coba ubah kriteria pencarian Anda.' : 'Belum ada kegiatan yang dijadwalkan.'; ?></p>
                </div>
            <?php else: ?>
                <div class="kegiatan-grid">
                    <?php foreach ($kegiatan as $item): ?>
                        <div class="kegiatan-card">
                            <div class="kegiatan-status <?php echo $item['status']; ?>">
                                <?php echo $item['status'] === 'upcoming' ? 'Akan Datang' : 'Selesai'; ?>
                            </div>
                            
                            <div class="kegiatan-content">
                                <h3><?php echo htmlspecialchars($item['judul']); ?></h3>
                                <p class="kegiatan-description"><?php echo htmlspecialchars(substr($item['deskripsi'], 0, 120)) . '...'; ?></p>
                                
                                <div class="kegiatan-details">
                                    <div class="detail-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>
                                            <?php 
                                            $tanggal_mulai = date('d M Y', strtotime($item['tanggal_mulai']));
                                            $tanggal_selesai = $item['tanggal_selesai'] ? date('d M Y', strtotime($item['tanggal_selesai'])) : null;
                                            
                                            if ($tanggal_selesai && $tanggal_selesai !== $tanggal_mulai) {
                                                echo $tanggal_mulai . ' - ' . $tanggal_selesai;
                                            } else {
                                                echo $tanggal_mulai;
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <?php if ($item['lokasi']): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?php echo htmlspecialchars($item['lokasi']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($item['nama'])): ?>
                                            <div class="detail-item">
                                                <i class="fas fa-user-tie"></i>
                                                <span><?php echo htmlspecialchars($item['nama']); ?></span>
                                            </div>
                                        <?php endif; ?>

                                    <?php if ($item['bidang']): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-tag"></i>
                                            <span><?php echo htmlspecialchars($item['bidang']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <button class="detail-btn" onclick="openKegiatanModal(<?php echo htmlspecialchars(json_encode($item)); ?>)">
                                    <i class="fas fa-info-circle"></i>
                                    Detail Kegiatan
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status) ? '&status=' . urlencode($status) : ''; ?>" class="pagination-btn">
                                <i class="fas fa-chevron-left"></i>
                                Sebelumnya
                            </a>
                        <?php endif; ?>

                        <div class="pagination-numbers">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status) ? '&status=' . urlencode($status) : ''; ?>" 
                                   class="pagination-number <?php echo $i == $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status) ? '&status=' . urlencode($status) : ''; ?>" class="pagination-btn">
                                Selanjutnya
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Detail Modal -->
<div id="kegiatanModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle"></h2>
            <button class="modal-close" onclick="closeKegiatanModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-status" id="modalStatus"></div>
            <div class="modal-details" id="modalDetails"></div>
            <div id="modalDescription"></div>
        </div>
    </div>
</div>

<script src="../js/kegiatan.js"></script>
<?php include 'footer.php'; ?>
