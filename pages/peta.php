<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../koneksi.php';
$db = new DatabaseConnection();
$puskesmasData = $db->getPuskesmasData();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Peta Kepuasan Puskesmas</h3>
    </div>
    <div class="card-body">
        <div id="map" style="height: 600px;"></div>
    </div>
</div>

<script>
(function() {
    // Initialize map when document is ready
    $(document).ready(function() {
        initializeMap();
    });

    function initializeMap() {
        // Remove existing map instance if exists
        if (window.map) {
            window.map.remove();
            window.map = null;
        }

        // Create new map instance
        window.map = L.map('map').setView([-6.966667, 110.416664], 12);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(window.map);

        // Get data from PHP
        const puskesmasData = <?php echo json_encode($puskesmasData); ?>;

        // Add zoom handler
        window.map.on('zoomend', function() {
            updateLayerByZoom();
        });

        // Show initial layer
        showKecamatanLayer();
    }

    // Utility functions
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


    // Modified calculateAverages to use dynamic data
    function calculateAverages() {
        const puskesmasData = <?php echo json_encode($puskesmasData); ?>;
        
        // Calculate Kelurahan averages
        const kelurahanAverages = {};
        const kelurahanCounts = {};
        
        puskesmasData.forEach(puskesmas => {
            if (!kelurahanAverages[puskesmas.kelurahan]) {
                kelurahanAverages[puskesmas.kelurahan] = 0;
                kelurahanCounts[puskesmas.kelurahan] = 0;
            }
            kelurahanAverages[puskesmas.kelurahan] += parseFloat(puskesmas.satisfaction);
            kelurahanCounts[puskesmas.kelurahan]++;
        });

        const kelurahanSatisfaction = {};
        for (let kel in kelurahanAverages) {
            kelurahanSatisfaction[kel] = Math.round(kelurahanAverages[kel] / kelurahanCounts[kel]);
        }

        // Calculate Kecamatan averages
        const kecamatanAverages = {};
        const kecamatanCounts = {};
        
        puskesmasData.forEach(puskesmas => {
            if (!kecamatanAverages[puskesmas.kecamatan]) {
                kecamatanAverages[puskesmas.kecamatan] = 0;
                kecamatanCounts[puskesmas.kecamatan] = 0;
            }
            kecamatanAverages[puskesmas.kecamatan] += parseFloat(puskesmas.satisfaction);
            kecamatanCounts[puskesmas.kecamatan]++;
        });

        const kecamatanSatisfaction = {};
        for (let kec in kecamatanAverages) {
            kecamatanSatisfaction[kec] = Math.round(kecamatanAverages[kec] / kecamatanCounts[kec]);
        }

        return { kelurahanSatisfaction, kecamatanSatisfaction };
    }

    // Modified marker display function
    function showPuskesmasMarkers() {
        clearMap();
        const puskesmasData = <?php echo json_encode($puskesmasData); ?>;
        
        puskesmasData.forEach(puskesmas => {
            L.marker(puskesmas.coords)
                .bindPopup(`
                    <div class="popup-content">
                        <h5>${puskesmas.name}</h5>
                        <p>Tingkat Kepuasan: ${puskesmas.satisfaction}%</p>
                        <p>Kecamatan: ${puskesmas.kecamatan}</p>
                        <p>Kelurahan: ${puskesmas.kelurahan}</p>
                    </div>
                `).addTo(window.map);
        });
    }
})();
</script>

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
</style>