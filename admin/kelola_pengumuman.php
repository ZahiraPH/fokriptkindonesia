<?php
session_start();
include '../config/database.php';

$page_title = 'Kelola Pengumuman';

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
                $status = $_POST['status'];
                $created_by = $_SESSION['user_id'];
                
                try {
                    $stmt = $pdo->prepare("INSERT INTO pengumuman (judul, deskripsi, status, created_by, created_at) VALUES (?, ?, ?, ?, NOW())");
                    $stmt->execute([$judul, $deskripsi, $status, $created_by]);

                    $success = 'Pengumuman berhasil ditambahkan';
                } catch (PDOException $e) {
                    $error = 'Gagal menambahkan pengumuman';
                }
                break;
                
            case 'edit':
                $id = $_POST['id'];
                $judul = trim($_POST['judul']);
                $deskripsi = trim($_POST['deskripsi']);
                $status = $_POST['status'];
                $created_by = trim($_POST['created_by']); 
                
                $cek = $pdo->prepare("SELECT COUNT(*) FROM pengurus WHERE id = ?");
                $cek->execute([$created_by]);
                if ($cek->fetchColumn() == 0) {
                    $error = 'ID Pengurus tidak ditemukan di tabel pengurus';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("UPDATE pengumuman SET judul = ?, deskripsi = ?, status = ?, created_by = ? WHERE id = ?");
                    $stmt->execute([$judul, $deskripsi, $status, $created_by, $id]);
                    $success = 'Pengumuman berhasil diupdate';
                } catch (PDOException $e) {
                    $error = 'Gagal mengupdate pengumuman';
                }
                break;
                
            case 'delete':
                $id = $_POST['id'];
                try {
                    $stmt = $pdo->prepare("DELETE FROM pengumuman WHERE id = ?");
                    $stmt->execute([$id]);
                    $success = 'Pengumuman berhasil dihapus';
                } catch (PDOException $e) {
                    $error = 'Gagal menghapus pengumuman';
                }
                break;
        }
    }
}

// Get search parameter
$search = $_GET['search'] ?? '';

// Get pengumuman data
try {
    if ($search) {
        $stmt = $pdo->prepare("
            SELECT p.*, g.nama AS nama_pengurus
            FROM pengumuman p
            LEFT JOIN pengurus g ON p.created_by = g.id
            WHERE p.judul LIKE ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute(["%$search%"]);
    } else {
        $stmt = $pdo->query("
            SELECT p.*, g.nama AS nama_pengurus
            FROM pengumuman p
            LEFT JOIN pengurus g ON p.created_by = g.id
            ORDER BY p.created_at DESC
        ");
    }

    $pengumuman_list = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Gagal mengambil data pengumuman';
}

include 'admin_header.php';
?>

<link rel="stylesheet" href="../css/kelola_pengumuman.css">

<div class="kelola-container">
    <div class="kelola-header">
        <h1>Kelola Pengumuman</h1>
        <button class="btn btn-primary" onclick="openModal('addModal')">
            <i class="fas fa-plus"></i> Tambah Pengumuman
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
                <input type="text" name="search" placeholder="Cari pengumuman..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-secondary">Cari</button>
            </div>
        </form>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Dibuat Oleh</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pengumuman_list)): ?>
                    <?php foreach ($pengumuman_list as $pengumuman): ?>
                        <tr>
                            <td><?php echo $pengumuman['id']; ?></td>
                            <td class="title-cell"><?php echo htmlspecialchars($pengumuman['judul']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $pengumuman['status']; ?>">
                                    <?php echo ucfirst($pengumuman['status']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($pengumuman['nama_pengurus'] ?? 'Admin'); ?></td>
                            <td><?php echo date('d M Y', strtotime($pengumuman['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-info btn-sm" onclick="viewPengumuman(<?php echo $pengumuman['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="editPengumuman(<?php echo $pengumuman['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deletePengumuman(<?php echo $pengumuman['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">Tidak ada data pengumuman</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Pengumuman Modal -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Tambah Pengumuman Baru</h2>
      <span class="close" onclick="closeModal('addModal')">&times;</span>
    </div>
    <div class="modal-body">
      <form method="POST" class="modal-form">
        <input type="hidden" name="action" value="add">

        <div class="form-group">
          <label for="judul">Judul Pengumuman</label>
          <input type="text" id="judul" name="judul" required>
        </div>

        <div class="form-group">
          <label for="deskripsi">Deskripsi</label>
          <textarea id="deskripsi" name="deskripsi" rows="5" required></textarea>
        </div>


        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Pengumuman Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit Pengumuman</h2>
      <span class="close" onclick="closeModal('editModal')">&times;</span>
    </div>
    <div class="modal-body">
      <form method="POST" class="modal-form">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" id="edit_id">

        <div class="form-group">
          <label for="edit_judul">Judul Pengumuman</label>
          <input type="text" id="edit_judul" name="judul" required>
        </div>
        
        <div class="form-group">
          <label for="edit_deskripsi">Deskripsi</label>
          <textarea id="edit_deskripsi" name="deskripsi" rows="5" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="edit_created_by">Dibuat Oleh</label>
            <input type="text" id="edit_created_by" name="created_by" required>
        </div>


        <div class="form-group">
          <label for="edit_status">Status</label>
          <select id="edit_status" name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
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
            <h2>Detail Pengumuman</h2>
            <span class="close" onclick="closeModal('viewModal')">&times;</span>
        </div>
        <div id="viewContent" class="modal-body">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script src="../js/kelola_pengumuman.js"></script>

</body>
</html>
