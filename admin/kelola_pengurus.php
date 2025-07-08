<?php
session_start();
include '../config/database.php';

$page_title = 'Kelola Pengurus';

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
                $id = trim($_POST['id']);
                $nama = trim($_POST['nama']);
                $username = trim($_POST['username']);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $kategori = $_POST['kategori'];
                $bidang = trim($_POST['bidang']);
                $sub_bidang = trim($_POST['sub_bidang']);
                $jabatan = trim($_POST['jabatan']);
                $asal_ptk = trim($_POST['asal_ptk']);
                $jenis_kelamin = $_POST['jenis_kelamin'];
                
                try {

                    // Validasi keunikan ID
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pengurus WHERE id = ?");
                    $stmt->execute([$id]);
                    if ($stmt->fetchColumn() > 0) {
                        $error = 'ID sudah digunakan';
                        break;
                    }
                    
                    // Validasi keunikan username
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pengurus WHERE username = ?");
                    $stmt->execute([$username]);
                    if ($stmt->fetchColumn() > 0) {
                        $error = 'Username sudah digunakan';
                        break;
                    }
                    
                    $stmt = $pdo->prepare("INSERT INTO pengurus (id, nama, username, password, kategori, bidang, sub_bidang, jabatan, asal_ptk, jenis_kelamin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$id, $nama, $username, $password, $kategori, $bidang, $sub_bidang, $jabatan, $asal_ptk, $jenis_kelamin]);
                    $success = 'Pengurus berhasil ditambahkan';
                } catch (PDOException $e) {
                    $error = 'Gagal menambahkan pengurus: ' . $e->getMessage();
                }
                break;
                
            case 'edit':
                $id = $_POST['id'];
                $nama = trim($_POST['nama']);
                $username = trim($_POST['username']);
                $kategori = $_POST['kategori'];
                $bidang = trim($_POST['bidang']);
                $sub_bidang = trim($_POST['sub_bidang']);
                $jabatan = trim($_POST['jabatan']);
                $asal_ptk = trim($_POST['asal_ptk']);
                $jenis_kelamin = $_POST['jenis_kelamin'];
                
                try {



                    // Check if username already exists for other users
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pengurus WHERE username = ? AND id != ?");
                    $stmt->execute([$username, $id]);
                    if ($stmt->fetchColumn() > 0) {
                        $error = 'Username sudah digunakan oleh pengurus lain';
                        break;
                    }
                    
                    // Update query with or without password
                    if (!empty($_POST['password'])) {
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE pengurus SET nama = ?, username = ?, password = ?, kategori = ?, bidang = ?, sub_bidang = ?, jabatan = ?, asal_ptk = ?, jenis_kelamin = ? WHERE id = ?");
                        $stmt->execute([$nama, $username, $password, $kategori, $bidang, $sub_bidang, $jabatan, $asal_ptk, $jenis_kelamin, $id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE pengurus SET nama = ?, username = ?, kategori = ?, bidang = ?, sub_bidang = ?, jabatan = ?, asal_ptk = ?, jenis_kelamin = ? WHERE id = ?");
                        $stmt->execute([$nama, $username, $kategori, $bidang, $sub_bidang, $jabatan, $asal_ptk, $jenis_kelamin, $id]);
                    }
                    
                    $success = 'Pengurus berhasil diupdate';
                } catch (PDOException $e) {
                    $error = 'Gagal mengupdate pengurus: ' . $e->getMessage();
                }
                break;
                
            case 'delete':
                $id = $_POST['id'];
                try {
                    // Check if pengurus exists first
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pengurus WHERE id = ?");
                    $stmt->execute([$id]);
                    if ($stmt->fetchColumn() == 0) {
                        $error = 'Pengurus tidak ditemukan';
                        break;
                    }
                    
                    $stmt = $pdo->prepare("DELETE FROM pengurus WHERE id = ?");
                    $stmt->execute([$id]);
                    
                    if ($stmt->rowCount() > 0) {
                        $success = 'Pengurus berhasil dihapus';
                    } else {
                        $error = 'Gagal menghapus pengurus';
                    }
                } catch (PDOException $e) {
                    $error = 'Gagal menghapus pengurus: ' . $e->getMessage();
                }
                break;
        }
    }
}

// Get search and filter parameters
$search = $_GET['search'] ?? '';
$filter_kategori = $_GET['filter_kategori'] ?? '';
$filter_bidang = $_GET['filter_bidang'] ?? '';

// Get pengurus data with filters
try {
    $where_conditions = [];
    $params = [];
    
    if ($search) {
        $where_conditions[] = "(nama LIKE ? OR username LIKE ? OR asal_ptk LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if ($filter_kategori) {
        $where_conditions[] = "kategori = ?";
        $params[] = $filter_kategori;
    }
    
    if ($filter_bidang) {
        $where_conditions[] = "bidang = ?";
        $params[] = $filter_bidang;
    }
    
    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
    
    $stmt = $pdo->prepare("SELECT * FROM pengurus $where_clause ORDER BY nama ASC");
    $stmt->execute($params);
    $pengurus_list = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Gagal mengambil data pengurus: ' . $e->getMessage();
}

include 'admin_header.php';
?>

<link rel="stylesheet" href="../css/kelola_pengurus.css">

<div class="kelola-container">
    <div class="kelola-header">
        <h1>Kelola Pengurus</h1>
        <button class="btn btn-primary" onclick="openModal('addModal')">
            <i class="fas fa-plus"></i> Tambah Pengurus
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
                <input type="text" name="search" placeholder="Cari pengurus..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-secondary">Cari</button>
            </div>
        </form>
    </div>

    <div class="filter-container">
        <form method="GET" class="filter-form">
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            
            <div class="filter-group">
                <label for="filter_kategori">Filter Kategori:</label>
                <select name="filter_kategori" id="filter_kategori" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <option value="Majelis Syuro" <?php echo ($filter_kategori ?? '') === 'Majelis Syuro' ? 'selected' : ''; ?>>Majelis Syuro</option>
                    <option value="Senat" <?php echo ($filter_kategori ?? '') === 'Senat' ? 'selected' : ''; ?>>Senat</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="filter_bidang">Filter Bidang:</label>
                <select name="filter_bidang" id="filter_bidang" onchange="this.form.submit()">
                    <option value="">Semua Bidang</option>
                    <?php
                    // Get unique bidang values
                    try {
                        $bidang_stmt = $pdo->query("SELECT DISTINCT bidang FROM pengurus WHERE bidang IS NOT NULL AND bidang != '' ORDER BY bidang");
                        $bidang_list = $bidang_stmt->fetchAll();
                        foreach ($bidang_list as $bidang_item) {
                            $selected = ($filter_bidang ?? '') === $bidang_item['bidang'] ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($bidang_item['bidang']) . "\" $selected>" . htmlspecialchars($bidang_item['bidang']) . "</option>";
                        }
                    } catch (PDOException $e) {
                        // Handle error silently
                    }
                    ?>
                </select>
            </div>
            
            <button type="button" class="btn btn-secondary btn-sm" onclick="clearFilters()">
                <i class="fas fa-times"></i> Clear Filter
            </button>
        </form>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Kategori</th>
                    <th>Jabatan</th>
                    <th>Asal PTK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pengurus_list)): ?>
                    <?php foreach ($pengurus_list as $pengurus): ?>
                        <tr>
                            <td><?php echo $pengurus['id']; ?></td>
                            <td class="name-cell"><?php echo htmlspecialchars($pengurus['nama']); ?></td>
                            <td><?php echo htmlspecialchars($pengurus['username']); ?></td>
                            <td>
                                <span class="kategori-badge kategori-<?php echo strtolower(str_replace(' ', '-', $pengurus['kategori'])); ?>">
                                    <?php echo htmlspecialchars($pengurus['kategori']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($pengurus['jabatan']); ?></td>
                            <td><?php echo htmlspecialchars($pengurus['asal_ptk']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-info btn-sm" onclick="viewPengurus('<?php echo $pengurus['id']; ?>')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="editPengurus('<?php echo $pengurus['id']; ?>')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deletePengurus('<?php echo $pengurus['id']; ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-data">Tidak ada data pengurus</td>
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
            <h2>Tambah Pengurus Baru</h2>
            <span class="close" onclick="closeModal('addModal')">&times;</span>
        </div>
        <form method="POST" class="modal-form">
            <input type="hidden" name="action" value="add">
            
            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" id="id" name="id" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Majelis Syuro">Majelis Syuro</option>
                        <option value="Senat">Senat</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="bidang">Bidang</label>
                <input type="text" id="bidang" name="bidang" required>
            </div>
            
            <div class="form-group">
                <label for="sub_bidang">Sub Bidang</label>
                <input type="text" id="sub_bidang" name="sub_bidang">
            </div>
            
            <div class="form-group">
                <label for="jabatan">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" required>
            </div>
            
            <div class="form-group">
                <label for="asal_ptk">Asal PTK</label>
                <input type="text" id="asal_ptk" name="asal_ptk" required>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2>Detail Pengurus</h2>
            <span class="close" onclick="closeModal('viewModal')">&times;</span>
        </div>
        <div id="viewContent" class="modal-body">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Pengurus</h2>
            <span class="close" onclick="closeModal('editModal')">&times;</span>
        </div>
        <form method="POST" class="modal-form">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <!-- Tambahkan field ID read-only untuk referensi -->

            <div class="form-group">
                <label for="edit_id_display">ID Pengurus</label>
                <input type="text" id="edit_id_display" name="id_display" readonly style="background-color: #f3f4f6; cursor: not-allowed;">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="edit_nama">Nama Lengkap</label>
                    <input type="text" id="edit_nama" name="nama" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_username">Username</label>
                    <input type="text" id="edit_username" name="username" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="edit_password">Password (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" id="edit_password" name="password">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_kategori">Kategori</label>
                    <select id="edit_kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Majelis Syuro">Majelis Syuro</option>
                        <option value="Senat">Senat</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                    <select id="edit_jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="edit_bidang">Bidang</label>
                <input type="text" id="edit_bidang" name="bidang" required>
            </div>
            
            <div class="form-group">
                <label for="edit_sub_bidang">Sub Bidang</label>
                <input type="text" id="edit_sub_bidang" name="sub_bidang">
            </div>
            
            <div class="form-group">
                <label for="edit_jabatan">Jabatan</label>
                <input type="text" id="edit_jabatan" name="jabatan" required>
            </div>
            
            <div class="form-group">
                <label for="edit_asal_ptk">Asal PTK</label>
                <input type="text" id="edit_asal_ptk" name="asal_ptk" required>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<script src="../js/kelola_pengurus.js"></script>

</body>
</html>