<?php
session_start();
include '../config/database.php';

$page_title = 'Kelola Dokumen';

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
                $uploaded_by = $_SESSION['user_id'];
                
                // Handle file upload
                $file = '';
                if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
                    $upload_dir = '../uploads/dokumen/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                    $file = 'dokumen_' . time() . '.' . $file_extension;
                    
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $file)) {
                        // File uploaded successfully
                    } else {
                        $error = 'Gagal mengupload file';
                        break;
                    }
                } else {
                    $error = 'File harus diupload';
                    break;
                }
                
                try {
                    $stmt = $pdo->prepare("INSERT INTO dokumen (judul, deskripsi, file, uploaded_by, created_at) VALUES (?, ?, ?, ?, NOW())");
                    $stmt->execute([$judul, $deskripsi, $file, $uploaded_by]);
                    $success = 'Dokumen berhasil ditambahkan';
                } catch (PDOException $e) {
                    $error = 'Gagal menambahkan dokumen';
                }
                break;
                
            case 'edit':
                $id = $_POST['id'];
                $judul = trim($_POST['judul']);
                $deskripsi = trim($_POST['deskripsi']);
                $uploaded_by = trim($_POST['uploaded_by']);
                
                // Handle file upload (optional for edit)
                $file = $_POST['existing_file'];
                if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
                    $upload_dir = '../uploads/dokumen/';
                    $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                    $new_file = 'dokumen_' . time() . '.' . $file_extension;
                    
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $new_file)) {
                        // Delete old file
                        if ($file && file_exists($upload_dir . $file)) {
                            unlink($upload_dir . $file);
                        }
                        $file = $new_file;
                    }
                }
                
                // Validasi: pastikan uploaded_by ada di tabel pengurus
                $cek = $pdo->prepare("SELECT COUNT(*) FROM pengurus WHERE id = ?");
                $cek->execute([$uploaded_by]);

                if ($cek->fetchColumn() == 0) {
                    $error = 'ID Pengurus tidak valid';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("UPDATE dokumen SET judul = ?, deskripsi = ?, file = ?, uploaded_by = ? WHERE id = ?");
                    $stmt->execute([$judul, $deskripsi, $file, $uploaded_by, $id]);
                    $success = 'Dokumen berhasil diupdate';
                } catch (PDOException $e) {
                    $error = 'Gagal mengupdate dokumen';
                }
                break;
                
            case 'delete':
                $id = $_POST['id'];
                try {
                    // Get file name to delete
                    $stmt = $pdo->prepare("SELECT file FROM dokumen WHERE id = ?");
                    $stmt->execute([$id]);
                    $dokumen = $stmt->fetch();
                    
                    if ($dokumen && $dokumen['file']) {
                        $file_path = '../uploads/dokumen/' . $dokumen['file'];
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }
                    
                    $stmt = $pdo->prepare("DELETE FROM dokumen WHERE id = ?");
                    $stmt->execute([$id]);
                    $success = 'Dokumen berhasil dihapus';
                } catch (PDOException $e) {
                    $error = 'Gagal menghapus dokumen';
                }
                break;
        }
    }
}

// Get search parameter
$search = $_GET['search'] ?? '';

// Get dokumen data
try {
    if ($search) {
        $stmt = $pdo->prepare("SELECT * FROM dokumen WHERE judul LIKE ? OR uploaded_by LIKE ? ORDER BY created_at DESC");
        $stmt->execute(["%$search%", "%$search%"]);
    } else {
        $stmt = $pdo->query("SELECT d.*, p.nama AS nama_pengurus FROM dokumen d LEFT JOIN pengurus p ON d.uploaded_by = p.id ORDER BY d.created_at DESC");
    }
    $dokumen_list = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Gagal mengambil data dokumen';
}

include 'admin_header.php';
?>

<link rel="stylesheet" href="../css/kelola_dokumen.css">

<div class="kelola-container">
    <div class="kelola-header">
        <h1>Kelola Dokumen</h1>
        <button class="btn btn-primary" onclick="openModal('addModal')">
            <i class="fas fa-plus"></i> Upload Dokumen
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
                <input type="text" name="search" placeholder="Cari dokumen..." value="<?php echo htmlspecialchars($search); ?>">
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
                    <th>File</th>
                    <th>Diupload Oleh</th>
                    <th>Tanggal Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dokumen_list)): ?>
                    <?php foreach ($dokumen_list as $dokumen): ?>
                        <tr>
                            <td><?php echo $dokumen['id']; ?></td>
                            <td class="title-cell"><?php echo htmlspecialchars($dokumen['judul']); ?></td>
                            <td>
                                <a href="../uploads/dokumen/<?php echo htmlspecialchars($dokumen['file']); ?>" 
                                   target="_blank" class="file-link">
                                    <i class="fas fa-file-alt"></i>
                                    <?php echo htmlspecialchars($dokumen['file']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($dokumen['nama_pengurus'] ?? 'Admin'); ?></td>
                            <td><?php echo date('d M Y', strtotime($dokumen['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-info btn-sm" onclick="viewDokumen(<?php echo $dokumen['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="editDokumen(<?php echo $dokumen['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="../uploads/dokumen/<?php echo htmlspecialchars($dokumen['file']); ?>" 
                                       download class="btn btn-success btn-sm">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm" onclick="deleteDokumen(<?php echo $dokumen['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="no-data">Tidak ada data dokumen</td>
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
      <h2>Upload Dokumen Baru</h2>
      <span class="close" onclick="closeModal('addModal')">&times;</span>
    </div>
    <div class="modal-body">
      <form method="POST" enctype="multipart/form-data" class="modal-form">
        <input type="hidden" name="action" value="add">

        <div class="form-group">
          <label for="judul">Judul Dokumen</label>
          <input type="text" id="judul" name="judul" required>
        </div>

        <div class="form-group">
          <label for="deskripsi">Deskripsi</label>
          <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
        </div>



        <div class="form-group">
          <label for="file">File Dokumen</label>
          <input type="file" id="file" name="file" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
          <small class="form-text">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</small>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Batal</button>
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit Dokumen</h2>
      <span class="close" onclick="closeModal('editModal')">&times;</span>
    </div>
    <div class="modal-body">
      <form method="POST" enctype="multipart/form-data" class="modal-form">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" id="edit_id">
        <input type="hidden" name="existing_file" id="edit_existing_file">

        <div class="form-group">
          <label for="edit_judul">Judul Dokumen</label>
          <input type="text" id="edit_judul" name="judul" required>
        </div>

        <div class="form-group">
          <label for="edit_deskripsi">Deskripsi</label>
          <textarea id="edit_deskripsi" name="deskripsi" rows="4" required></textarea>
        </div>

        <div class="form-group">
          <label for="edit_uploaded_by">Diupload Oleh</label>
          <input type="text" id="edit_uploaded_by" name="uploaded_by" required>
        </div>

        <div class="form-group">
          <label for="edit_file">File Dokumen (Kosongkan jika tidak ingin mengubah)</label>
          <input type="file" id="edit_file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
          <div id="current_file" style="margin-top: 0.5rem;"></div>
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
            <h2>Detail Dokumen</h2>
            <span class="close" onclick="closeModal('viewModal')">&times;</span>
        </div>
        <div id="viewContent" class="modal-body">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script src="../js/kelola_dokumen.js"></script>

</body>
</html>
