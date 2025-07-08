<?php 
include 'Header.php';
include '../config/database.php';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchCondition = '';
$params = [];

if (!empty($search)) {
    $searchCondition = "WHERE judul LIKE :search OR deskripsi LIKE :search OR uploaded_by LIKE :search";
    $params[':search'] = "%$search%";
}

// Get total count
$countSql = "SELECT COUNT(*) FROM dokumen $searchCondition";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get dokumen data
$sql = "SELECT dokumen.*, pengurus.bidang 
        FROM dokumen 
        LEFT JOIN pengurus ON dokumen.uploaded_by = pengurus.id 
        $searchCondition 
        ORDER BY dokumen.created_at DESC 
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$dokumen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../css/Dokumen.css">

<main class="main-content">
    <section class="page-header">
        <div class="container">
            <h1>Dokumen & Informasi Resmi</h1>
            <p>Kumpulan dokumen penting dan informasi resmi FOKRI PTK</p>
        </div>
    </section>

    <section class="dokumen-section">
        <div class="container">
            <div class="search-section">
                <form action="dokumen.php" method="GET" class="search-form">
                    <div class="search-input-group" style="position: relative;">
                        <input
                            type="text"
                            id="searchInput"
                            name="search"
                            placeholder="Cari dokumen..."
                            value="<?php echo htmlspecialchars($search ?? ''); ?>"
                            class="search-input"
                            autocomplete="off"
                        >
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <?php if (empty($dokumen)): ?>
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Tidak ada dokumen ditemukan</h3>
                    <p><?php echo !empty($search) ? 'Coba kata kunci lain untuk pencarian Anda.' : 'Belum ada dokumen yang dipublikasikan.'; ?></p>
                </div>
            <?php else: ?>
                <div class="dokumen-grid">
                    <?php foreach ($dokumen as $doc): ?>
                        <div class="dokumen-card">
                            <div class="dokumen-icon">
                                <?php
                                $extension = strtolower(pathinfo($doc['file'], PATHINFO_EXTENSION));
                                $iconClass = 'fas fa-file';
                                $iconColor = '#64748b';

                                switch ($extension) {
                                    case 'pdf':
                                        $iconClass = 'fas fa-file-pdf';
                                        $iconColor = '#ef4444';
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $iconClass = 'fas fa-file-word';
                                        $iconColor = '#2563eb';
                                        break;
                                    case 'xls':
                                    case 'xlsx':
                                        $iconClass = 'fas fa-file-excel';
                                        $iconColor = '#16a34a';
                                        break;
                                    case 'ppt':
                                    case 'pptx':
                                        $iconClass = 'fas fa-file-powerpoint';
                                        $iconColor = '#ea580c';
                                        break;
                                }
                                ?>
                                <i class="<?php echo $iconClass; ?>" style="color: <?php echo $iconColor; ?>"></i>
                            </div>

                            <div class="dokumen-content">
                                <h3><?php echo htmlspecialchars($doc['judul']); ?></h3>
                                <p class="dokumen-description"><?php echo htmlspecialchars(substr($doc['deskripsi'], 0, 120)) . '...'; ?></p>

                                <div class="dokumen-meta">
                                    <?php if (!empty($doc['bidang'])): ?>
                                        <span class="dokumen-uploader">
                                            <i class="fas fa-user"></i>
                                            <?php echo htmlspecialchars($doc['bidang']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="dokumen-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('d M Y', strtotime($doc['created_at'])); ?>
                                    </span>
                                </div>

                                <div class="dokumen-actions">

                                    <a href="detail_dokumen.php?id=<?php echo $doc['id']; ?>" 
                                       class="btn-preview" target="_blank">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </a>
                                    <a href="../uploads/dokumen/<?php echo htmlspecialchars($doc['file']); ?>" 
                                       download class="btn-download">
                                        <i class="fas fa-download"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="pagination-btn">
                                <i class="fas fa-chevron-left"></i>
                                Sebelumnya
                            </a>
                        <?php endif; ?>

                        <div class="pagination-numbers">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                                   class="pagination-number <?php echo $i == $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="pagination-btn">
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

<script src="../js/dokumen.js"></script>
<?php include 'footer.php'; ?>
