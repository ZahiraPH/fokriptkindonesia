<?php 
include 'header.php';
include '../config/database.php';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Search
$search = $_GET['search'] ?? '';
$searchCondition = '';
$params = [];

if (!empty($search)) {
    $searchCondition = "WHERE judul LIKE :search OR deskripsi LIKE :search OR penulis LIKE :search";
    $params[':search'] = "%$search%";
}

// Total count
$countSql = "SELECT COUNT(*) FROM berita $searchCondition";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get berita data
$sql = "SELECT berita.*, pengurus.bidang FROM berita LEFT JOIN pengurus ON berita.penulis = pengurus.id $searchCondition ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$berita = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../css/berita.css">

<main class="main-content">
    <section class="page-header">
        <div class="container">
            <h1>Berita & Kabar Terbaru</h1>
            <p>Informasi terkini seputar kegiatan FOKRI PTK</p>
        </div>
    </section>

    <section class="berita-section">
        <div class="container">
            <div class="search-section">
                <form method="GET" class="search-form" autocomplete="off">
                    <div class="search-input-group">
                        <input type="text" id="searchInput" name="search" placeholder="Cari berita..." value="<?php echo htmlspecialchars($search); ?>" class="search-input">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <?php if (empty($berita)): ?>
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3>Tidak ada berita ditemukan</h3>
                    <p><?php echo !empty($search) ? 'Coba kata kunci lain untuk pencarian Anda.' : 'Belum ada berita yang dipublikasikan.'; ?></p>
                </div>
            <?php else: ?>
                <div class="berita-grid" id="beritaGrid">
                    <?php foreach ($berita as $item): ?>
                        <article class="berita-card">
                            <div class="berita-image">
                                <?php if ($item['gambar']): ?>
                                    <img src="../uploads/berita/<?php echo htmlspecialchars($item['gambar']); ?>" alt="<?php echo htmlspecialchars($item['judul']); ?>">
                                <?php else: ?>
                                    <div class="placeholder-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="berita-content">
                                <h3><?php echo htmlspecialchars($item['judul']); ?></h3>
                                <p class="berita-excerpt"><?php echo htmlspecialchars(substr($item['deskripsi'], 0, 150)) . '...'; ?></p>
                                <div class="berita-meta">
                                    <span class="berita-author">
                                        <i class="fas fa-user"></i>
                                        <?php echo htmlspecialchars($item['bidang'] ?: 'Admin'); ?>
                                    </span>
                                    <span class="berita-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('d M Y', strtotime($item['created_at'])); ?>
                                    </span>
                                </div>
                                <a href="detail_berita.php?id=<?php echo $item['id']; ?>" class="read-more-btn">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="pagination-btn">
                                <i class="fas fa-chevron-left"></i> Sebelumnya
                            </a>
                        <?php endif; ?>

                        <div class="pagination-numbers">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                                   class="pagination-number <?php echo $i == $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="pagination-btn">
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<script src="../js/berita.js"></script>
<?php include 'footer.php'; ?>
