<?php
session_start();
include '../config/database.php';

$page_title = 'Kelola Berita';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$success = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $judul = trim($_POST['judul']);
                $deskripsi = trim($_POST['deskripsi']);
                $penulis = $_SESSION['user_id'];

                
                // Handle file upload
                $gambar = '';
                if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
                    $upload_dir = '../uploads/berita/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                    $gambar = 'berita_' . time() . '.' . $file_extension;
                    
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar)) {
                        // File uploaded successfully
                    } else {
                        $error = 'Gagal mengupload gambar';
                        break;
                    }
                }
                
                try {
                    $stmt = $pdo->prepare("INSERT INTO berita (judul, deskripsi, gambar, penulis, created_at) VALUES (?, ?, ?, ?, NOW())");
                    $stmt->execute([$judul, $deskripsi, $gambar, $penulis]);
                    $success = 'Berita berhasil ditambahkan';
                } catch (PDOException $e) {
                    $error = 'Gagal menambahkan berita';
                }
                break;
                
            case 'delete':
                $id = $_POST['id'];
                try {
                    // Get image filename to delete
                    $stmt = $pdo->prepare("SELECT gambar FROM berita WHERE id = ?");
                    $stmt->execute([$id]);
                    $berita = $stmt->fetch();
                    
                    if ($berita && $berita['gambar']) {
                        $image_path = '../uploads/berita/' . $berita['gambar'];
                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                    
                    $stmt = $pdo->prepare("DELETE FROM berita WHERE id = ?");
                    $stmt->execute([$id]);
                    $success = 'Berita berhasil dihapus';
                } catch (PDOException $e) {
                    $error = 'Gagal menghapus berita';
                }
                break;
            case 'edit':
                $id = $_POST['id'];
                $judul = trim($_POST['judul']);
                $deskripsi = trim($_POST['deskripsi']);
                $penulis = trim($_POST['penulis']);
               
                // Validasi apakah ID pengurus valid
                $stmt = $pdo->query("SELECT id FROM pengurus");
                $valid_pengurus = $stmt->fetchAll(PDO::FETCH_COLUMN);

                if (!in_array($penulis, $valid_pengurus)) {
                    $error = 'ID penulis tidak valid. Harus merupakan ID dari tabel pengurus.';
                    break;
                }

                // Handle file upload
                $gambar = $_POST['existing_gambar'];
                if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
                    $upload_dir = '../uploads/berita/';
                    $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                    $new_gambar = 'berita_' . time() . '.' . $file_extension;
                    
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $new_gambar)) {
                        // Delete old image
                        if ($gambar && file_exists($upload_dir . $gambar)) {
                            unlink($upload_dir . $gambar);
                        }
                        $gambar = $new_gambar;
                    }
                }
                
                try {
                    $stmt = $pdo->prepare("UPDATE berita SET judul = ?, deskripsi = ?, gambar = ?, penulis = ? WHERE id = ?");
                    $stmt->execute([$judul, $deskripsi, $gambar, $penulis, $id]);
                    $success = 'Berita berhasil diupdate';
                } catch (PDOException $e) {
                    $error = 'Gagal mengupdate berita';
                }
                break;
        }
    }
}

// Get search parameter
$search = $_GET['search'] ?? '';

// Get berita data
try {
    if ($search) {
        $stmt = $pdo->prepare("SELECT berita.*, pengurus.bidang FROM berita LEFT JOIN pengurus ON berita.penulis = pengurus.id WHERE judul LIKE ? OR pengurus.bidang LIKE ? ORDER BY created_at DESC");
        $stmt->execute(["%$search%", "%$search%"]);

    } else {
        $stmt = $pdo->query("SELECT berita.*, pengurus.bidang FROM berita LEFT JOIN pengurus ON berita.penulis = pengurus.id ORDER BY created_at DESC");

    }
    $berita_list = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Gagal mengambil data berita';
}

include 'admin_header.php';
?>

<link rel="stylesheet" href="../css/kelola_berita.css">

<div class="kelola-container">
    <div class="kelola-header">
        <h1>Kelola Berita</h1>
        <button class="btn btn-primary" onclick="openModal('addModal')">
            <i class="fas fa-plus"></i> Tambah Berita
        </button>
    </div>
    
    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <div class="search-container">
        <form method="GET" class="search-form">
            <div class="search-input-group">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Cari berita..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-secondary">Cari</button>
            </div>
        </form>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($berita_list)): ?>
                    <?php foreach ($berita_list as $berita): ?>
                        <tr>
                            <td><?php echo $berita['id']; ?></td>
                            <td>
                                <?php if ($berita['gambar']): ?>
                                    <img src="../uploads/berita/<?php echo htmlspecialchars($berita['gambar']); ?>" 
                                         alt="Gambar Berita" class="table-image">
                                <?php else: ?>
                                    <span class="no-image">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td class="title-cell"><?php echo htmlspecialchars($berita['judul']); ?></td>
                            <td><?php echo htmlspecialchars($berita['bidang'] ?? 'Admin'); ?></td>
                            <td><?php echo date('d M Y', strtotime($berita['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-info btn-sm" onclick="viewBerita(<?php echo $berita['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="editBerita(<?php echo $berita['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteBerita(<?php echo $berita['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="no-data">Tidak ada data berita</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Tambah Berita Baru</h2>
      <span class="close" onclick="closeModal('addModal')">&times;</span>
    </div>
    <div class="modal-body">
      <form method="POST" enctype="multipart/form-data" class="modal-form">
        <input type="hidden" name="action" value="add">

        <div class="form-group">
          <label for="judul">Judul Berita</label>
          <input type="text" id="judul" name="judul" required>
        </div>

        <div class="form-group">
          <label for="deskripsi">Deskripsi</label>
          <textarea id="deskripsi" name="deskripsi" rows="5" required></textarea>
        </div>



        <div class="form-group">
          <label for="gambar">Gambar</label>
          <input type="file" id="gambar" name="gambar" accept="image/*">
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Berita</h2>
            <span class="close" onclick="closeModal('editModal')">&times;</span>
        </div>

        <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" class="modal-form">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="existing_gambar" id="edit_existing_gambar">
                
                <div class="form-group">
                    <label for="edit_judul">Judul Berita</label>
                    <input type="text" id="edit_judul" name="judul" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_deskripsi">Deskripsi</label>
                    <textarea id="edit_deskripsi" name="deskripsi" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                <label for="edit_penulis">ID Penulis (ID Pengurus)</label>
                <input type="text" id="edit_penulis" name="penulis" required>
                <small class="form-warning" style="color: red; display: none;" id="warning_penulis">
                    ⚠ ID penulis tidak ditemukan di data pengurus!
                </small>
                </div>

                
                <div class="form-group">
                    <label for="edit_gambar">Gambar (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="file" id="edit_gambar" name="gambar" accept="image/*">
                    <div id="current_image" style="margin-top: 0.5rem;"></div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2>Detail Berita</h2>
            <span class="close" onclick="closeModal('viewModal')">&times;</span>
        </div>
        <div id="viewContent" class="modal-body">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
  // Daftar ID pengurus dikirim dari PHP ke JavaScript
  <?php
    $pengurus_ids = $pdo->query("SELECT id FROM pengurus")->fetchAll(PDO::FETCH_COLUMN);
    $encoded_ids = json_encode($pengurus_ids);
  ?>
  window.validPengurusIDs = <?= $encoded_ids ?>;
</script>


<script src="../js/kelola_berita.js"></script>

</body>
</html>
