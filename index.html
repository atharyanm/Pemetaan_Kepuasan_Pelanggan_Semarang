<?php
session_start();
if(isset($_SESSION['username'])) {
    header("Location: homepage.php");
    exit();
}

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if($username === 'Yankes' && $password === 'Pahlawan79') {
        $_SESSION['username'] = $username;
        header("Location: homepage.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIG Kepuasan Pelanggan Puskesmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/animate-css/animate.css/animate.min.css">

    <style>
        /* Base styles */
        body {
            overflow: hidden;
        }

        /* Welcome section styles */
        .welcome-section {
            position: absolute;
            left: 5%;
            top: 42%;
            transform: translateY(-50%);
            color: white;
            max-width: 500px;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.8rem;
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .welcome-text {
            font-size: 1rem;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1.2rem;
        }

        /* Stats styles */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.8rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 0.8rem;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.15);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.2rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Login card styles */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            width: 320px;
            margin-right: 3.5rem;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-header img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            padding: 3px;
            border: 2px solid #0d6efd;
            margin-bottom: 0.8rem;
        }

        .form-floating {
            margin-bottom: 0.75rem;
        }

        .form-floating input {
            border-radius: 8px;
        }

        .btn-login {
            width: 100%;
            padding: 0.8rem;
            border-radius: 8px;
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
        }

        .contact-info {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .contact-info p {
            color: #6c757d;
            font-size: 0.8rem;
            margin-bottom: 0.3rem;
        }

        .contact-info i {
            color: #0d6efd;
            width: 20px;
        }

        /* Alert styles */
        .alert {
            border-radius: 8px;
            padding: 0.8rem;
            margin-bottom: 1rem;
        }

        /* Password toggle button */
        .btn-link {
            color: #6c757d;
            text-decoration: none;
        }

        .btn-link:hover {
            color: #0d6efd;
        }
    </style>

<!-- Update body background gradient -->
<body class="d-flex align-items-center justify-content-end vh-100" style="
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.4), rgba(10, 88, 202, 0.4)), 
    url('foto/dkk.jpg') no-repeat center center fixed; 
    background-size: cover;">
    
    <!-- Add Brand Overlay -->
    <div class="welcome-section animate__animated animate__fadeInLeft">
        <h1 class="welcome-title">Selamat Datang di<br>Portal Kepuasan Puskesmas</h1>
        
        <div class="stat-card mb-3">
            <p class="welcome-text">
                Sistem Informasi Geografis untuk memantau dan mengevaluasi tingkat kepuasan pelayanan 
                di seluruh Puskesmas Kota Semarang.
            </p>
        </div>
        
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number">39</div>
                <div class="stat-label">Puskesmas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">16</div>
                <div class="stat-label">Kecamatan</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">1.6jt+</div>
                <div class="stat-label">Penduduk</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Pelayanan</div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
    <div class="row justify-content-end">
        <div class="col-auto">
            <div class="login-card animate__animated animate__fadeInRight">
                <div class="login-header">
                    <img src="foto/logoDinkes.jpg" alt="Logo Dinkes" class="animate__animated animate__pulse animate__infinite">
                    <h4 class="text-primary mb-1">Selamat Datang</h4>
                    <p class="text-muted small">Silakan masuk untuk melanjutkan</p>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger animate__animated animate__shakeX">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        <label for="username"><i class="fas fa-user text-primary"></i> Username</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fas fa-lock text-primary"></i> Password</label>
                        <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted pe-3" 
                                onclick="togglePassword()" style="z-index: 5;">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <button type="submit" name="submit" class="btn btn-login btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                    </button>
                </form>

                <div class="contact-info text-center">
                    <p><i class="fas fa-map-marker-alt me-2"></i>Jl. Pandanaran No.79, Mugassari</p>
                    <p><i class="fas fa-phone me-2"></i>(024) 8318070</p>
                    <p><i class="fas fa-globe me-2"></i>dinkes.semarangkota.go.id</p>
                </div>
            </div>
        </div>
    </div>
</div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('minimap').setView([-6.9932, 110.4203], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([-6.9932, 110.4203])
            .addTo(map)
            .bindPopup('<strong>Dinas Kesehatan Kota Semarang</strong><br>Jl. Pandanaran No.79, Mugassari');
    
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>