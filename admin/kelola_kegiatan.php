<?php
session_start();
include '../config/database.php';

$page_title = 'Kelola Kegiatan';

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
                $tanggal_mulai = $_POST['tanggal_mulai'];
                $tanggal_selesai = $_POST['tanggal_selesai'];
                $lokasi = trim($_POST['lokasi']);
                                $status = $_POST['status'];

                $penanggung_jawab = $_SESSION['user_id'];
                $bidang = $_SESSION['bidang'];

                
                try {
                    $stmt = $pdo->prepare("INSERT INTO kegiatan (
                        judul, deskripsi, tanggal_mulai, tanggal_selesai, lokasi, penanggung_jawab, bidang, status, created_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

                    $stmt->execute([
                        $judul, $deskripsi, $tanggal_mulai, $tanggal_selesai, $lokasi,
                        $penanggung_jawab, $bidang, $status
                    ]);
                                
                    $success = 'Kegiatan berhasil ditambahkan';
                } catch (PDOException $e) {
                    $error = 'Gagal menambahkan kegiatan';
                }
                break;
                
case 'edit':
    $id = $_POST['id'];
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $lokasi = trim($_POST['lokasi']);
    $penanggung_jawab = trim($_POST['penanggung_jawab']);
    $bidang = trim($_POST['bidang']);
    $status = $_POST['status'];

    // Validasi apakah penanggung_jawab ada di tabel pengurus
    $stmt = $pdo->prepare("SELECT bidang FROM pengurus WHERE id = ?");
    $stmt->execute([$penanggung_jawab]);
    $pengurus = $stmt->fetch();

    if (!$pengurus) {
        $error = 'ID pengurus tidak ditemukan';
        break;
    }

    if (strcasecmp($pengurus['bidang'], $bidang) !== 0) {
        $error = 'Bidang tidak sesuai dengan data pengurus';
        break;
    }

    try {
        $stmt = $pdo->prepare("UPDATE kegiatan SET judul = ?, deskripsi = ?, tanggal_mulai = ?, tanggal_selesai = ?, lokasi = ?, penanggung_jawab = ?, bidang = ?, status = ? WHERE id = ?");
        $stmt->execute([
            $judul, $deskripsi, $tanggal_mulai, $tanggal_selesai, $lokasi,
            $penanggung_jawab, $bidang, $status, $id
        ]);
        $success = 'Kegiatan berhasil diupdate';
    } catch (PDOException $e) {
        $error = 'Gagal mengupdate kegiatan';
    }
    break;

                
            case 'delete':
                $id = $_POST['id'];
                try {
                    $stmt = $pdo->prepare("DELETE FROM kegiatan WHERE id = ?");
                    $stmt->execute([$id]);
                    $success = 'Kegiatan berhasil dihapus';
                } catch (PDOException $e) {
                    $error = 'Gagal menghapus kegiatan';
                }
                break;
        }
    }
}

// Get search parameter
$search = $_GET['search'] ?? '';

// Get kegiatan data
try {
    if ($search) {
        $stmt = $pdo->prepare("SELECT * FROM kegiatan WHERE judul LIKE ? OR lokasi LIKE ? OR penanggung_jawab LIKE ? ORDER BY tanggal_mulai DESC");
        $stmt->execute(["%$search%", "%$search%", "%$search%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM kegiatan ORDER BY tanggal_mulai DESC");
    }
    $kegiatan_list = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Gagal mengambil data kegiatan';
}

include 'admin_header.php';
?>

<link rel="stylesheet" href="../css/kelola_kegiatan.css">

<div class="kelola-container">
    <div class="kelola-header">
        <h1>Kelola Kegiatan</h1>
        <button class="btn btn-primary" onclick="openModal('addModal')">
            <i class="fas fa-plus"></i> Tambah Kegiatan
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
                <input type="text" name="search" placeholder="Cari kegiatan..." value="<?php echo htmlspecialchars($search); ?>">
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
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($kegiatan_list)): ?>
                    <?php foreach ($kegiatan_list as $kegiatan): ?>
                        <tr>
                            <td><?php echo $kegiatan['id']; ?></td>
                            <td class="title-cell"><?php echo htmlspecialchars($kegiatan['judul']); ?></td>
                            <td><?php echo date('d M Y', strtotime($kegiatan['tanggal_mulai'])); ?></td>
                            <td><?php echo date('d M Y', strtotime($kegiatan['tanggal_selesai'])); ?></td>
                            <td><?php echo htmlspecialchars($kegiatan['lokasi']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $kegiatan['status']; ?>">
                                    <?php echo ucfirst($kegiatan['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-info btn-sm" onclick="viewKegiatan(<?php echo $kegiatan['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="editKegiatan(<?php echo $kegiatan['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteKegiatan(<?php echo $kegiatan['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-data">Tidak ada data kegiatan</td>
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
      <h2>Tambah Kegiatan Baru</h2>
      <span class="close" onclick="closeModal('addModal')">&times;</span>
    </div>

    <!-- ✅ Tambahkan .modal-body agar bisa scroll -->
    <div class="modal-body">
      <form method="POST" class="modal-form">
        <input type="hidden" name="action" value="add">

        <div class="form-group">
          <label for="judul">Judul Kegiatan</label>
          <input type="text" id="judul" name="judul" required>
        </div>

        <div class="form-group">
          <label for="deskripsi">Deskripsi</label>
          <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="tanggal_mulai">Tanggal Mulai</label>
            <input type="date" id="tanggal_mulai" name="tanggal_mulai" required>
          </div>

          <div class="form-group">
            <label for="tanggal_selesai">Tanggal Selesai</label>
            <input type="date" id="tanggal_selesai" name="tanggal_selesai" required>
          </div>
        </div>

        <div class="form-group">
          <label for="lokasi">Lokasi</label>
          <input type="text" id="lokasi" name="lokasi" required>
        </div>

        <input type="hidden" id="penanggung_jawab" name="penanggung_jawab" value="<?php echo $_SESSION['user_id']; ?>">

        <input type="hidden" id="bidang" name="bidang" value="<?php echo htmlspecialchars($_SESSION['bidang']); ?>">

          <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
              <option value="upcoming">Upcoming</option>
              <option value="finished">Finished</option>
            </select>
          </div>
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
      <h2>Edit Kegiatan</h2>
      <span class="close" onclick="closeModal('editModal')">&times;</span>
    </div>

    <!-- Tambahkan modal-body untuk bagian yang di-scroll -->
    <div class="modal-body">
      <form method="POST" class="modal-form">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" id="edit_id">

        <div class="form-group">
          <label for="edit_judul">Judul Kegiatan</label>
          <input type="text" id="edit_judul" name="judul" required>
        </div>

        <div class="form-group">
          <label for="edit_deskripsi">Deskripsi</label>
          <textarea id="edit_deskripsi" name="deskripsi" rows="4" required></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="edit_tanggal_mulai">Tanggal Mulai</label>
            <input type="date" id="edit_tanggal_mulai" name="tanggal_mulai" required>
          </div>
          <div class="form-group">
            <label for="edit_tanggal_selesai">Tanggal Selesai</label>
            <input type="date" id="edit_tanggal_selesai" name="tanggal_selesai" required>
          </div>
        </div>

        <div class="form-group">
          <label for="edit_lokasi">Lokasi</label>
          <input type="text" id="edit_lokasi" name="lokasi" required>
        </div>

        <div class="form-group">
          <label for="edit_penanggung_jawab">Penanggung Jawab</label>
          <input type="text" id="edit_penanggung_jawab" name="penanggung_jawab" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="edit_bidang">Bidang</label>
            <input type="text" id="edit_bidang" name="bidang" required>
          </div>

          <div class="form-group">
            <label for="edit_status">Status</label>
            <select id="edit_status" name="status" required>
              <option value="upcoming">Upcoming</option>
              <option value="finished">Finished</option>
            </select>
          </div>
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
            <h2>Detail Kegiatan</h2>
            <span class="close" onclick="closeModal('viewModal')">&times;</span>
        </div>
        <div id="viewContent" class="modal-body">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script src="../js/kelola_kegiatan.js"></script>

</body>
</html>
