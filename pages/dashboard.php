<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once '../koneksi.php';
$db = new DatabaseConnection();
$puskesmasData = $db->getPuskesmasData();

// Calculate statistics
$totalPuskesmas = count($puskesmasData);
$kecamatanCount = count(array_unique(array_column($puskesmasData, 'kecamatan')));
$kelurahanCount = count(array_unique(array_column($puskesmasData, 'kelurahan')));
$avgSatisfaction = round(array_sum(array_column($puskesmasData, 'satisfaction')) / $totalPuskesmas, 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIG Kepuasan Pelanggan Puskesmas</title>
    <style>
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .stat-label {
            color: #666;
            font-size: 1rem;
        }

        .satisfaction-overview {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-top: 2rem;
        }

        .satisfaction-chart {
            margin-top: 1.5rem;
            height: 300px;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .quick-stat {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <p class="text-muted">Ringkasan data kepuasan pelanggan Puskesmas Kota Semarang</p>
            </div>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <div class="stat-value"><?php echo $totalPuskesmas; ?></div>
                <div class="stat-label">Total Puskesmas</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stat-value"><?php echo $kecamatanCount; ?></div>
                <div class="stat-label">Kecamatan</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-city"></i>
                </div>
                <div class="stat-value"><?php echo $kelurahanCount; ?></div>
                <div class="stat-label">Kelurahan</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-smile"></i>
                </div>
                <div class="stat-value"><?php echo $avgSatisfaction; ?>%</div>
                <div class="stat-label">Rata-rata Kepuasan</div>
            </div>
        </div>

        <div class="satisfaction-overview">
            <h4>Statistik Kepuasan</h4>
            <div class="quick-stats">
                <div class="quick-stat">
                    <h5>Kepuasan Tertinggi</h5>
                    <p class="mb-0"><?php echo max(array_column($puskesmasData, 'satisfaction')); ?>%</p>
                </div>
                <div class="quick-stat">
                    <h5>Kepuasan Terendah</h5>
                    <p class="mb-0"><?php echo min(array_column($puskesmasData, 'satisfaction')); ?>%</p>
                </div>
                <div class="quick-stat">
                    <h5>Total Responden</h5>
                    <p class="mb-0"><?php echo array_sum(array_column($puskesmasData, 'jumlah_responden')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update page title when loaded through AJAX
        document.title = 'Dashboard - SIG Kepuasan Pelanggan Puskesmas';

        function loadPage(page) {
            const titles = {
                'dashboard': 'Dashboard',
                'peta': 'Peta Kepuasan',
                'puskesmas': 'Data Puskesmas',
                'kepuasan': 'Data Kepuasan',
                'info': 'Tentang Kami'
            };
        
            $('#main-content').load(`pages/${page}.php`, function(response, status, xhr) {
                if (status === 'success') {
                    document.title = `${titles[page]} - SIG Kepuasan Pelanggan Puskesmas`;
                    $('#page-title').text(titles[page]);
                } else {
                    console.error(`Error loading page: ${xhr.status} ${xhr.statusText}`);
                }
            });
        }
    </script>
</body>
</html>