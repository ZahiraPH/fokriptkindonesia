<?php
session_start();
include '../config/database.php';

$error = '';
$success = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../admin/dashboard_admin.php');
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM pengurus WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();
            
            if ($user && $password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['kategori'] = $user['kategori'];
                $_SESSION['jabatan'] = $user['jabatan'];
                $_SESSION['bidang'] = $user['bidang'];

                
                header('Location: ../admin/dashboard_admin.php');
                exit();
            } else {
                $error = 'Username atau password salah';
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FOKRI Connect</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-background">
            <div class="background-overlay"></div>
        </div>
        
        <div class="login-content">
            <div class="login-card">
                <div class="login-header">
                    <div class="logo-section">
                        <img src="../aset/logo-majelis.png" alt="Logo Majelis" class="logo">
                        <img src="../aset/logo-fokri.png" alt="Logo FOKRI" class="logo">
                    </div>
                    <h1>FOKRI Connect</h1>
                    <p>Login Admin Panel</p>
                </div>
                
                <div class="login-form-container">
                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="login-form" id="loginForm">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" id="username" name="username" placeholder="Masukkan username" 
                                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="login-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            Masuk
                        </button>
                    </form>
                </div>
                
                <div class="login-footer">
                    <a href="Beranda.php" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Website
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../js/login.js"></script>
</body>
</html>
