<?php 
include 'header.php';
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
    $searchCondition .= " AND (judul LIKE :search OR deskripsi LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($status)) {
    $searchCondition .= " AND status = :status";
    $params[':status'] = $status;
}

// Get total count
$countSql = "SELECT COUNT(*) FROM pengumuman WHERE 1=1 $searchCondition";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get pengumuman data
$sql = "SELECT * FROM pengumuman WHERE 1=1 $searchCondition ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$pengumuman = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../css/pengumuman.css">

<main class="main-content">
    <section class="page-header">
        <div class="container">
            <h1>Pengumuman FOKRI PTK</h1>
            <p>Informasi penting dan pengumuman resmi dari FOKRI PTK</p>
        </div>
    </section>

    <section class="pengumuman-section">
        <div class="container">
            <div class="filter-section">
                <form method="GET" class="filter-form">
                    <div class="search-group">
                        <div class="search-input-group" style="position: relative;">
                        <input type="text" name="search" placeholder="Cari pengumuman..." value="<?php echo htmlspecialchars($search); ?>" class="search-input" id="searchInput">
                        <div id="suggestion-box" class="suggestion-box"></div>
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <select name="status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Aktif</option>
                            <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Tidak Aktif</option>
                        </select>
                        <button type="submit" class="filter-btn">Filter</button>
                    </div>
                </form>
            </div>

            <?php if (empty($pengumuman)): ?>
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3>Tidak ada pengumuman ditemukan</h3>
                    <p><?php echo !empty($search) || !empty($status) ? 'Coba ubah kriteria pencarian Anda.' : 'Belum ada pengumuman yang dipublikasikan.'; ?></p>
                </div>
            <?php else: ?>
                <div class="pengumuman-grid">
                    <?php foreach ($pengumuman as $item): ?>
                        <div class="pengumuman-card">
                            <div class="pengumuman-status <?php echo $item['status']; ?>">
                                <?php echo $item['status'] === 'active' ? 'Aktif' : 'Tidak Aktif'; ?>
                            </div>
                            
                            <div class="pengumuman-content">
                                <h3><?php echo htmlspecialchars($item['judul']); ?></h3>
                                <p class="pengumuman-excerpt"><?php echo htmlspecialchars(substr($item['deskripsi'], 0, 150)) . '...'; ?></p>
                                
                                <div class="pengumuman-meta">
                                    <span class="pengumuman-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('d M Y', strtotime($item['created_at'])); ?>
                                    </span>
                                    <span class="pengumuman-time">
                                        <i class="fas fa-clock"></i>
                                        <?php echo date('H:i', strtotime($item['created_at'])); ?>
                                    </span>
                                </div>
                                
                                <a class="read-more-btn" href="detail_pengumuman.php?id=<?php echo $item['id']; ?>">
                                    <i class="fas fa-eye"></i>
                                    Baca Selengkapnya
                                </a>

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

<!-- Modal for full pengumuman -->
<div id="pengumumanModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle"></h2>
            <button class="modal-close" onclick="closePengumumanModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-status" id="modalStatus"></div>
            <div class="modal-meta">
                <span id="modalDate"></span>
                <span id="modalTime"></span>
            </div>
            <div id="modalContent"></div>
        </div>
    </div>
</div>

<script src="../js/pengumuman.js"></script>
<?php include 'footer.php'; ?>
