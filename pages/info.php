<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi - SIG Kepuasan Pelanggan Puskesmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .info-box {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .info-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .info-box .info-box-icon {
            transition: all 0.3s ease;
        }

        .info-box:hover .info-box-icon {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi tentang kami</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Sistem Informasi Geografis Kepuasan Pelanggan Puskesmas</h4>
                            <p class="text-justify">
                                Sistem ini dikembangkan untuk memvisualisasikan tingkat kepuasan pelanggan di berbagai 
                                Puskesmas yang tersebar di Kota Semarang. Dengan menggunakan pemetaan geografis, kami 
                                menyajikan data kepuasan pelanggan secara interaktif dan mudah dipahami.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h4>Tujuan</h4>
                            <ul>
                                <li>Menyajikan data kepuasan pelanggan Puskesmas secara visual</li>
                                <li>Memudahkan analisis distribusi tingkat kepuasan di berbagai wilayah</li>
                                <li>Mendukung pengambilan keputusan untuk peningkatan layanan kesehatan</li>
                                <li>Memberikan transparansi informasi kepada masyarakat</li>
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4>Fitur Utama</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-map"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Pemetaan Interaktif</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-chart-bar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Visualisasi Data</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-hospital"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Info Puskesmas</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger"><i class="fas fa-chart-pie"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Analisis Kepuasan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
</body>
