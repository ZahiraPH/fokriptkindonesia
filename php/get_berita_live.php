<?php
require '../config/database.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM berita 
        WHERE judul LIKE :keyword OR deskripsi LIKE :keyword OR penulis LIKE :keyword 
        ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':keyword' => "%$keyword%"]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $item):
?>
<article class="berita-card">
  <div class="berita-image">
    <?php if ($item['gambar']): ?>
      <img src="../uploads/berita/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['judul']) ?>">
    <?php else: ?>
      <div class="placeholder-image">
        <i class="fas fa-image"></i>
      </div>
    <?php endif; ?>
  </div>
  <div class="berita-content">
    <h3><?= htmlspecialchars($item['judul']) ?></h3>
    <p class="berita-excerpt"><?= htmlspecialchars(substr($item['deskripsi'], 0, 150)) . '...'; ?></p>
    <div class="berita-meta">
      <span class="berita-author"><i class="fas fa-user"></i> <?= htmlspecialchars($item['penulis'] ?: 'Admin') ?></span>
      <span class="berita-date"><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($item['created_at'])) ?></span>
    </div>
    <a href="detail_berita.php?id=<?= $item['id'] ?>" class="read-more-btn">Baca Selengkapnya</a>
  </div>
</article>
<?php endforeach; ?>
<?php if (empty($data)): ?>
  <div class="no-results">
    <div class="no-results-icon"><i class="fas fa-newspaper"></i></div>
    <h3>Tidak ada berita ditemukan</h3>
    <p>Coba kata kunci lain untuk pencarian Anda.</p>
  </div>
<?php endif; ?>
