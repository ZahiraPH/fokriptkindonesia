<?php 
include 'Header.php';
include '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: Dokumen.php');
    exit();
}

try {
    $stmt = $pdo->prepare("
    SELECT dokumen.*, pengurus.nama, pengurus.bidang 
    FROM dokumen 
    LEFT JOIN pengurus ON dokumen.uploaded_by = pengurus.id 
    WHERE dokumen.id = ?
");

    $stmt->execute([$id]);
    $dokumen = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$dokumen) {
        header('Location: Dokumen.php');
        exit();
    }
} catch (PDOException $e) {
    header('Location: Dokumen.php');
    exit();
}

$extension = strtolower(pathinfo($dokumen['file'], PATHINFO_EXTENSION));
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

<link rel="stylesheet" href="../css/detail.css">

<main class="main-content">
    <section class="detail-header">
        <div class="container">
            <nav class="breadcrumb">
                <a href="beranda.php">Beranda</a>
                <span class="separator">></span>
                <a href="Dokumen.php">Dokumen</a>
                <span class="separator">></span>
                <span class="current"><?php echo htmlspecialchars($dokumen['judul']); ?></span>
            </nav>
        </div>
    </section>

    <section class="detail-content">
        <div class="container">
            <article class="detail-article">
                <header class="article-header">
                    <div class="dokumen-icon-large">
                        <i class="<?php echo $iconClass; ?>" style="color: <?php echo $iconColor; ?>"></i>
                    </div>
                    <h1><?php echo htmlspecialchars($dokumen['judul']); ?></h1>
                    <div class="article-meta">
                        <?php if (!empty($dokumen['bidang'])): ?>
                            <span class="meta-item">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($dokumen['bidang']); ?>
                            </span>
                        <?php endif; ?>
                        <span class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <?php echo date('d M Y', strtotime($dokumen['created_at'])); ?>
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-file"></i>
                            <?php echo strtoupper($extension); ?>
                        </span>
                    </div>
                </header>

                <div class="article-body">
                    <h3>Deskripsi Dokumen</h3>
                    <?php echo nl2br(htmlspecialchars($dokumen['deskripsi'])); ?>
                </div>

                <div class="dokumen-actions">

                    <a href="../uploads/dokumen/<?php echo htmlspecialchars($dokumen['file']); ?>" 
                       class="btn-preview" target="_blank">
                        <i class="fas fa-eye"></i>
                        Preview Dokumen
                    </a>
                </div>

                <div class="article-actions">
                    <a href="Dokumen.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Dokumen
                    </a>
                </div>
            </article>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
