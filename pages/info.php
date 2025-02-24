<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
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