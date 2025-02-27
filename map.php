<?php
session_start();
require_once 'koneksi.php';

// Initialize database connection
$db = new DatabaseConnection();
$puskesmasData = $db->getPuskesmasData();

// Process data for visualization
$kecamatanData = [];
foreach ($puskesmasData as $puskesmas) {
    $kecamatan = $puskesmas['kecamatan'];
    if (!isset($kecamatanData[$kecamatan])) {
        $kecamatanData[$kecamatan] = [
            'total' => 0,
            'count' => 0,
            'responden' => 0,
            'puskesmas' => []
        ];
    }
    
    $kecamatanData[$kecamatan]['total'] += $puskesmas['satisfaction'];
    $kecamatanData[$kecamatan]['count']++;
    $kecamatanData[$kecamatan]['responden'] += $puskesmas['jumlah_responden'];
    $kecamatanData[$kecamatan]['puskesmas'][] = $puskesmas;
}

// Prepare data for Chart.js
$chartLabels = array_keys($kecamatanData);
$chartData = array_map(function($data) {
    return round($data['total'] / $data['count'], 1);
}, array_values($kecamatanData));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisasi Kepuasan - SIG Puskesmas</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .chart-container { 
            position: relative;
            height: 400px; 
            margin-bottom: 2rem; 
        }
        .chart-type-btn {
            transition: all 0.3s ease;
        }
        .chart-type-btn.active {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Dashboard Kepuasan Puskesmas</h5>
            </div>
            <div class="card-body">
                <!-- Chart Type Selector -->
                <div class="btn-group mb-4" role="group">
                    <button type="button" class="btn btn-outline-primary chart-type-btn active" data-type="satisfaction">
                        <i class="fas fa-smile"></i> Kepuasan
                    </button>
                    <button type="button" class="btn btn-outline-primary chart-type-btn" data-type="respondents">
                        <i class="fas fa-users"></i> Responden
                    </button>
                    <button type="button" class="btn btn-outline-primary chart-type-btn" data-type="puskesmas">
                        <i class="fas fa-hospital"></i> Puskesmas
                    </button>
                </div>

                <!-- Chart Container -->
                <div class="chart-container">
                    <canvas id="dashboardChart"></canvas>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="text-center d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="alert alert-danger d-none"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        let currentChart = null;
        const chartData = {
            satisfaction: {
                labels: <?php echo json_encode(array_keys($kecamatanData)); ?>,
                // Convert data to array and ensure numbers
                data: Object.values(<?php echo json_encode(array_map(function($data) {
                    return floatval($data['count'] > 0 ? round($data['total'] / $data['count'], 1) : 0);
                }, $kecamatanData)); ?>),
                title: 'Tingkat Kepuasan per Kecamatan (%)'
            },
            respondents: {
                labels: <?php echo json_encode(array_keys($kecamatanData)); ?>,
                // Convert to array of numbers
                data: Object.values(<?php echo json_encode(array_map(function($data) {
                    return intval($data['responden']);
                }, $kecamatanData)); ?>),
                title: 'Jumlah Responden per Kecamatan'
            },
            puskesmas: {
                labels: <?php echo json_encode(array_keys($kecamatanData)); ?>,
                // Convert to array of numbers
                data: Object.values(<?php echo json_encode(array_map(function($data) {
                    return intval($data['count']);
                }, $kecamatanData)); ?>),
                title: 'Jumlah Puskesmas per Kecamatan'
            }
        };

        // Add debug logging
        console.log('Chart Data:', chartData);

        function getColorForValue(value) {
            if (value >= 80) return 'rgba(40, 167, 69, 0.8)';
            if (value >= 60) return 'rgba(255, 193, 7, 0.8)';
            return 'rgba(220, 53, 69, 0.8)';
        }

        function createChart(type) {
            try {
                const ctx = document.getElementById('dashboardChart').getContext('2d');
                const data = chartData[type];

                // Validate data
                if (!Array.isArray(data.data)) {
                    console.error('Invalid data format:', data);
                    throw new Error('Data is not an array');
                }

                if (currentChart) {
                    currentChart.destroy();
                }

                // Create background colors array
                const backgroundColor = type === 'satisfaction' 
                    ? data.data.map(value => getColorForValue(value))
                    : Array(data.data.length).fill('rgba(13, 110, 253, 0.8)');

                currentChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: data.title,
                            data: data.data,
                            backgroundColor: backgroundColor,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return type === 'satisfaction' ? value + '%' : value;
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: data.title
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Chart creation error:', error);
                document.getElementById('errorMessage').textContent = error.message;
                document.getElementById('errorMessage').classList.remove('d-none');
            }
        }

        // Initialize with satisfaction chart
        createChart('satisfaction');

        // Handle chart type buttons
        document.querySelectorAll('.chart-type-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.chart-type-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                createChart(this.dataset.type);
            });
        });
    });
    </script>
</body>
</html>