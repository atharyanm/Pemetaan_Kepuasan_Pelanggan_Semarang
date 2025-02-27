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
</head>
<body class="d-flex align-items-center justify-content-center vh-100" style="background: linear-gradient(135deg, #1976d2, #064789);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card glassmorphism p-4 shadow-lg animate__animated animate__fadeInUp">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center justify-content-center flex-column text-center bg-light p-4 rounded-start">
                            <img src="foto/logoDinkes.jpg" alt="Logo" class="mb-3" style="width: 100px;">
                            <h4 class="text-primary fw-bold">SIG Kepuasan Pelanggan</h4>
                            <p class="text-muted">Sistem Informasi Geografis Puskesmas</p>
                            <div id="minimap" class="w-100 rounded shadow-sm" style="height: 350px;"></div>
                            <div class="mt-3 text-start">
                                <h5 class="text-primary">Dinas Kesehatan Kota Semarang</h5>
                                <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Jl. Pandanaran No.79, Mugassari</p>
                                <p class="mb-1"><i class="fas fa-phone me-2"></i>(024) 8318070</p>
                                <p class="mb-1"><i class="fas fa-envelope me-2"></i>dinkes@semarangkota.go.id</p>
                                <p class="mb-0"><i class="fas fa-globe me-2"></i>dinkes.semarangkota.go.id</p>
                            </div>
                        </div>
                        <div class="col-md-6 p-4 d-flex flex-column justify-content-center">
                            <h3 class="text-center text-primary fw-bold animate__animated animate__fadeIn">Login</h3>
                            <?php if(isset($error)): ?>
                                <div class="alert alert-danger mt-3 animate__animated animate__shakeX">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            <form method="POST" action="" class="mt-3 animate__animated animate__fadeInUp">
                                <div class="mb-3 input-group">
                                    <span class="input-group-text bg-primary text-white"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" placeholder="Username" name="username" required>
                                </div>
                                <div class="mb-3 input-group">
                                    <span class="input-group-text bg-primary text-white"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                                    <button type="button" class="input-group-text bg-secondary text-white" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary w-100 fw-bold shadow-sm animate__animated animate__pulse animate__infinite">
                                    <i class="fas fa-sign-in-alt me-2"></i> Masuk
                                </button>
                            </form>
                        </div>
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