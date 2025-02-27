<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../koneksi.php';
$db = new DatabaseConnection();
$puskesmasData = $db->getPuskesmasData();
$kecamatanData = $db->getKecamatanData(); // Pastikan fungsi ini tersedia
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Kepuasan Puskesmas</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .popup-content {
            padding: 5px;
            text-align: center;
        }
        .popup-content h5 {
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        .popup-content p {
            margin: 2px 0;
        }
        #map { height: 600px; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Peta Kepuasan Puskesmas</h3>
    </div>
    <div class="card-body">
        <div id="map"></div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    initializeMap();
});

function initializeMap() {
    window.map = L.map('map').setView([-6.966667, 110.416664], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.map);
    
    const puskesmasData = <?php echo json_encode($puskesmasData); ?>;
    const kecamatanData = <?php echo json_encode($kecamatanData); ?>;
    
    window.map.on('zoomend', function() {
        updateLayerByZoom();
    });
    
    showKecamatanLayer();
}

function getColor(satisfaction) {
    return satisfaction > 90 ? '#006837' :
           satisfaction > 80 ? '#1a9850' :
           satisfaction > 70 ? '#66bd63' :
           satisfaction > 60 ? '#fd8d3c' :
                             '#d73027';
}

function clearMap() {
    window.map.eachLayer((layer) => {
        if (!(layer instanceof L.TileLayer)) {
            window.map.removeLayer(layer);
        }
    });
}

function calculateAverages() {
    const puskesmasData = <?php echo json_encode($puskesmasData); ?>;
    const kecamatanSatisfaction = {};
    const kecamatanCounts = {};
    
    puskesmasData.forEach(puskesmas => {
        if (!kecamatanSatisfaction[puskesmas.kecamatan]) {
            kecamatanSatisfaction[puskesmas.kecamatan] = 0;
            kecamatanCounts[puskesmas.kecamatan] = 0;
        }
        kecamatanSatisfaction[puskesmas.kecamatan] += parseFloat(puskesmas.satisfaction);
        kecamatanCounts[puskesmas.kecamatan]++;
    });
    
    for (let kec in kecamatanSatisfaction) {
        kecamatanSatisfaction[kec] = Math.round(kecamatanSatisfaction[kec] / kecamatanCounts[kec]);
    }
    
    return { kecamatanSatisfaction };
}

function showKecamatanLayer() {
    clearMap();
    const { kecamatanSatisfaction } = calculateAverages();
    const kecamatanData = <?php echo json_encode($kecamatanData); ?>;
    
    L.geoJSON(kecamatanData, {
        style: function(feature) {
            const satisfaction = kecamatanSatisfaction[feature.properties.name] || 0;
            return {
                fillColor: getColor(satisfaction),
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.7
            };
        },
        onEachFeature: function(feature, layer) {
            const satisfaction = kecamatanSatisfaction[feature.properties.name] || 0;
            layer.bindPopup(`
                <div class="popup-content">
                    <h5>Kecamatan ${feature.properties.name}</h5>
                    <p>Rata-rata Kepuasan: ${satisfaction}%</p>
                </div>
            `);
        }
    }).addTo(window.map);
}

function showPuskesmasMarkers() {
    clearMap();
    const puskesmasData = <?php echo json_encode($puskesmasData); ?>;
    
    puskesmasData.forEach(puskesmas => {
        if (Array.isArray(puskesmas.coords) && puskesmas.coords.length === 2) {
            L.marker(puskesmas.coords)
                .bindPopup(`
                    <div class="popup-content">
                        <h5>${puskesmas.name}</h5>
                        <p>Tingkat Kepuasan: ${puskesmas.satisfaction}%</p>
                        <p>Kecamatan: ${puskesmas.kecamatan}</p>
                    </div>
                `).addTo(window.map);
        } else {
            console.warn('Invalid coordinates for:', puskesmas);
        }
    });
}

function updateLayerByZoom() {
    const zoom = window.map.getZoom();
    if (zoom < 13) {
        showKecamatanLayer();
    } else {
        showPuskesmasMarkers();
    }
}

console.log('Puskesmas Data:', <?php echo json_encode($puskesmasData); ?>);
console.log('Kecamatan Data:', <?php echo json_encode($kecamatanData); ?>);
</script>
</body>
</html>
