<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../koneksi.php';
$db = new DatabaseConnection();
$puskesmasData = $db->getPuskesmasData();

// Process data for charts
$kecamatanStats = [];
$satisfactionRanges = [
    'Sangat Puas (90-100%)' => ['count' => 0, 'range' => [90, 100], 'color' => '#0d6efd'],
    'Puas (80-89%)' => ['count' => 0, 'range' => [80, 89.99], 'color' => '#0dcaf0'],
    'Cukup (70-79%)' => ['count' => 0, 'range' => [70, 79.99], 'color' => '#20c997'],
    'Kurang (60-69%)' => ['count' => 0, 'range' => [60, 69.99], 'color' => '#ffc107'],
    'Sangat Kurang (<60%)' => ['count' => 0, 'range' => [0, 59.99], 'color' => '#adb5bd']
];

foreach ($puskesmasData as $data) {
    if (!isset($kecamatanStats[$data['kecamatan']])) {
        $kecamatanStats[$data['kecamatan']] = [
            'total' => 0,
            'count' => 0,
            'puskesmas' => []
        ];
    }
    
    $kecamatanStats[$data['kecamatan']]['total'] += $data['satisfaction'];
    $kecamatanStats[$data['kecamatan']]['count']++;
    $kecamatanStats[$data['kecamatan']]['puskesmas'][] = [
        'name' => $data['name'],
        'satisfaction' => $data['satisfaction'],
        'responden' => $data['jumlah_responden']
    ];

    foreach ($satisfactionRanges as $level => &$info) {
        if ($data['satisfaction'] >= $info['range'][0] && 
            $data['satisfaction'] <= $info['range'][1]) {
            $info['count']++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagram Kepuasan - SIG Kepuasan Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: 400px;
        }

        .filter-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-chart-type {
            padding: 8px 16px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            background: #e9ecef;
            color: #495057;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-chart-type:hover {
            background: #0d6efd;
            color: white;
            transform: translateY(-2px);
        }

        .btn-chart-type.active {
            background: #0d6efd;
            color: white;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Visualisasi Data Kepuasan</h3>
            </div>
            <div class="card-body">
                <div class="filter-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button class="btn-chart-type active" data-type="doughnut">
                                <i class="fas fa-chart-pie"></i> Tingkat Kepuasan
                            </button>
                            <button class="btn-chart-type" data-type="bar">
                                <i class="fas fa-chart-bar"></i> Per Kecamatan
                            </button>
                            <button class="btn-chart-type" data-type="line">
                                <i class="fas fa-chart-line"></i> Tren Kepuasan
                            </button>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="animationToggle" checked>
                            <label class="form-check-label" for="animationToggle">Animasi</label>
                        </div>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const chartData = {
        satisfaction: {
            labels: <?= json_encode(array_keys($satisfactionRanges)) ?>,
            datasets: [{
                data: <?= json_encode(array_column($satisfactionRanges, 'count')) ?>,
                backgroundColor: <?= json_encode(array_column($satisfactionRanges, 'color')) ?>,
                borderWidth: 2
            }]
        },
        kecamatan: {
            labels: <?= json_encode(array_keys($kecamatanStats)) ?>,
            datasets: [{
                label: 'Rata-rata Kepuasan (%)',
                data: <?= json_encode(array_map(function($stat) {
                    return round($stat['total'] / $stat['count'], 2);
                }, $kecamatanStats)) ?>,
                backgroundColor: '#0d6efd',
                borderColor: '#0d6efd',
                borderWidth: 1
            }]
        },
        trend: {
            labels: <?= json_encode(array_keys($kecamatanStats)) ?>,
            datasets: [{
                label: 'Tingkat Kepuasan (%)',
                data: <?= json_encode(array_map(function($stat) {
                    return round($stat['total'] / $stat['count'], 2);
                }, $kecamatanStats)) ?>,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        }
    };

    const chartOptions = {
        doughnut: {
            type: 'doughnut',
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.raw * 100) / total).toFixed(1);
                                return `${context.raw} Puskesmas (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 2000
                }
            }
        },
        bar: {
            type: 'bar',
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        },
        line: {
            type: 'line',
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Tingkat Kepuasan (%)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Kecamatan'
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        }
    };

    let currentChart = new Chart(
        document.getElementById('mainChart'),
        {
            type: 'doughnut',
            data: chartData.satisfaction,
            options: chartOptions.doughnut.options
        }
    );

    document.querySelectorAll('.btn-chart-type').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.btn-chart-type').forEach(btn => 
                btn.classList.remove('active')
            );
            this.classList.add('active');
            
            const chartType = this.dataset.type;
            updateChart(chartType);
        });
    });

    function updateChart(type) {
        const animate = document.getElementById('animationToggle').checked;
        currentChart.destroy();
        
        const chartConfig = {
            type: type,
            data: type === 'doughnut' ? chartData.satisfaction : 
                  type === 'bar' ? chartData.kecamatan : chartData.trend,
            options: {
                ...chartOptions[type].options,
                animation: {
                    duration: animate ? chartOptions[type].options.animation.duration : 0
                }
            }
        };
        
        currentChart = new Chart(document.getElementById('mainChart'), chartConfig);
    }

    window.addEventListener('resize', () => {
        if (currentChart) {
            currentChart.resize();
        }
    });
    </script>
</body>
</html>