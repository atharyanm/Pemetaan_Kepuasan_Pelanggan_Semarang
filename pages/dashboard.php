<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once '../koneksi.php';
$db = new DatabaseConnection();
$puskesmasData = $db->getPuskesmasData();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIG Kepuasan Pelanggan Puskesmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body {
            background: #f4f6f9;
        }
        .card {
            margin-bottom: 2rem;
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .stat-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background: white;
            transition: transform 0.3s ease-in-out;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .stat-card .icon {
            font-size: 2rem;
            color: #1976d2;
        }
        .stat-card .info {
            text-align: right;
        }
        .stat-card .info h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .stat-card .info p {
            margin: 0;
            color: #6c757d;
        }
        #map {
            height: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card text-center p-4">
                    <h5 class="card-title fw-bold">Dashboard Kepuasan Pelanggan</h5>
                    <p class="card-text">Pantau tingkat kepuasan pelanggan di berbagai puskesmas di Kota Semarang.</p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="info">
                        <h4>37</h4>
                        <p>Puskesmas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="info">
                        <h4>16</h4>
                        <p>Kecamatan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="info">
                        <h4>177</h4>
                        <p>Kelurahan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Peta Kepuasan Puskesmas</h5>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-6.9932, 110.4203], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
    </script>
</body>
</html>
