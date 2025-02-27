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
    <title>Visualisasi Kepuasan Puskesmas</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-routing-machine/dist/leaflet-routing-machine.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-control-geocoder/dist/Control.Geocoder.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.markercluster/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.markercluster/dist/MarkerCluster.Default.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-locatecontrol/dist/L.Control.Locate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-minimap/dist/Control.MiniMap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-geosearch/dist/geosearch.min.css">
    
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- //Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav ml-3">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <i class="fas fa-chart-pie fa-fw ml-3 mr-2"></i>
                <span class="brand-text font-weight-light">Pemetaan Kepuasan</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" data-page="dashboard">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-page="peta">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Peta Kepuasan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-page="puskesmas">
                                <i class="nav-icon fas fa-hospital"></i>
                                <p>Data Puskesmas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-page="kepuasan">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Data Kepuasan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-page="info">
                                <i class="nav-icon fas fa-info"></i>
                                <p>Tentang Kami</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0" id="page-title">Dashboard</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div id="main-content">
                        <!-- Content will be loaded here -->
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <p class="text-center text-muted">
                                &copy; 2025 Sistem Informasi Geografis Kepuasan Pelanggan Puskesmas Kota Semarang
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-routing-machine/dist/leaflet-routing-machine.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-control-geocoder/dist/Control.Geocoder.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-locatecontrol/dist/L.Control.Locate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-minimap/dist/Control.MiniMap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-geosearch/dist/geosearch.min.js"></script>
    
    <script>
        function loadPage(page) {
            switch(page) {
                case 'dashboard':
                    $('#main-content').load('pages/dashboard.php', function() {
                        document.title = 'Dashboard';
                    });
                    break;
                case 'peta':
                    $('#main-content').load('pages/peta.php', function() {
                        document.title = 'Peta Kepuasan';
                    });
                    break;
                case 'puskesmas':
                    $('#main-content').load('pages/puskesmas.php', function() {
                        document.title = 'Data Puskesmas';
                    });
                    break;
                case 'kepuasan':
                    $('#main-content').load('pages/kepuasan.php', function() {
                        document.title = 'Data Kepuasan';
                    });
                    break;
                case 'info':
                    $('#main-content').load('pages/info.php', function() {
                        document.title = 'Tentang Kami';
                    });
                    break;
                default:
                    $('#main-content').load('pages/dashboard.php', function() {
                        document.title = 'Dashboard';
                    });
            }
        }
        
        $(document).ready(function() {
            // Load dashboard by default
            loadPage('dashboard');
        
            // Handle menu clicks
            $('.nav-link').click(function(e) {
                e.preventDefault();

                if ($(this).attr('href') === 'logout.php') {
                    // Show logout confirmation
                    Swal.fire({
                        title: 'Konfirmasi Logout',
                        text: "Apakah Anda yakin ingin keluar?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Logout!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'logout.php';
                        }
                    });
                } else {
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');
            
                    const page = $(this).data('page');
                    $('#page-title').text($(this).find('p').text());
                    loadPage(page);
                }
            });
        });
    </script>
</body>
</html>