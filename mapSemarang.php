<?php
class DatabaseConnection {
    private $spreadsheetUrl;
    private $data;

    public function __construct() {
        $spreadsheetId = "1xtInJ3Rtuqq9LKfZXJ7RNr76Nqq_L5ASkjBvu8FRjsM";
        $gid = "0"; // First sheet
        $this->spreadsheetUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=csv&gid={$gid}";
    }

    private function cleanCoordinate($coord) {
        $coord = str_replace(['-', ' '], '', $coord);
        $coord = str_replace(',', '.', $coord);
        if (strpos($coord, '.') !== false) {
            $parts = explode('.', $coord);
            if (count($parts) > 2) {
                $coord = $parts[0] . '.' . implode('', array_slice($parts, 1));
            }
        }
        return $coord;
    }

    public function fetchData() {
        try {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);

            $csvData = file_get_contents($this->spreadsheetUrl, false, $context);
            if ($csvData === false) {
                throw new Exception("Failed to fetch data from spreadsheet");
            }

            $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
            $headers = array_shift($rows);
            $this->data = array_map(function($row) use ($headers) {
                if (count($row) !== count($headers)) {
                    return null;
                }
                $data = array_combine($headers, $row);
                if (isset($data['latitude'], $data['longitude'])) {
                    $data['latitude'] = $this->cleanCoordinate($data['latitude']);
                    $data['longitude'] = $this->cleanCoordinate($data['longitude']);
                }
                return $data;
            }, $rows);
            $this->data = array_filter($this->data);
            return $this->data;

        } catch (Exception $e) {
            error_log("Error fetching data: " . $e->getMessage());
            return [];
        }
    }

    public function getKecamatanData() {
        if (!$this->data) {
            $this->fetchData();
        }

        $kecamatanData = [];
        foreach ($this->data as $row) {
            if (isset($row['kecamatan'], $row['tingkat_kepuasan'], $row['jumlah_responden'])) {
                $kecamatan = trim($row['kecamatan']);
                if (!isset($kecamatanData[$kecamatan])) {
                    $kecamatanData[$kecamatan] = [
                        'total' => 0,
                        'count' => 0,
                        'responden' => 0
                    ];
                }
                $kecamatanData[$kecamatan]['total'] += floatval($row['tingkat_kepuasan']);
                $kecamatanData[$kecamatan]['count']++;
                $kecamatanData[$kecamatan]['responden'] += intval($row['jumlah_responden']);
            }
        }

        $results = [];
        foreach ($kecamatanData as $kecamatan => $data) {
            if ($data['count'] > 0) {
                $results[$kecamatan] = [
                    'average' => round($data['total'] / $data['count'], 2),
                    'total_responden' => $data['responden'],
                    'puskesmas_count' => $data['count']
                ];
            }
        }

        return $results;
    }

    public function getPuskesmasLayerData() {
        if (!$this->data) {
            $this->fetchData();
        }

        $puskesmasLayerData = [];
        foreach ($this->data as $row) {
            if (isset($row['nama_puskesmas'], $row['latitude'], $row['longitude'], 
                     $row['tingkat_kepuasan'], $row['kecamatan'], $row['kelurahan'],
                     $row['tanggal_update'], $row['jumlah_responden'], $row['keterangan'])) {
                
                $puskesmasLayerData[] = [
                    'name' => trim($row['nama_puskesmas']),
                    'coords' => [
                        floatval($row['latitude']),
                        floatval($row['longitude'])
                    ],
                    'satisfaction' => floatval($row['tingkat_kepuasan']),
                    'kecamatan' => trim($row['kecamatan']),
                    'kelurahan' => trim($row['kelurahan']),
                    'tanggal_update' => trim($row['tanggal_update']),
                    'jumlah_responden' => intval($row['jumlah_responden']),
                    'keterangan' => trim($row['keterangan'])
                ];
            }
        }
        return $puskesmasLayerData;
    }
}

// Fetch data
$db = new DatabaseConnection();
$kecamatanData = $db->getKecamatanData();
$puskesmasData = $db->getPuskesmasLayerData();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Puskesmas</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
.legend {
    background: white;
    padding: 8px;
    border-radius: 4px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
}

.legend i {
    width: 18px;
    height: 18px;
    float: left;
    margin-right: 8px;
    opacity: 0.7;
}

.legend h4 {
    margin: 0 0 5px;
    font-size: 14px;
}

.popup-content {
    padding: 10px;
    min-width: 200px;
}

.popup-content h5 {
    margin: 0 0 8px 0;
    color: #333;
    font-weight: bold;
}

.popup-content p {
    margin: 4px 0;
    color: #666;
}
</style>
</head>
<body>
    <h1>Peta Puskesmas dan Rata-rata Kepuasan</h1>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
const MapManager = {
    map: null,
    kecamatanLayer: null,
    puskesmasLayer: null,

    init() {
        // Initialize map centered on Semarang
        this.map = L.map('map').setView([-7.005145, 110.438124], 12);
        
        // Add base tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(this.map);

        // Initialize layers
        this.kecamatanLayer = L.layerGroup().addTo(this.map);
        this.puskesmasLayer = L.layerGroup().addTo(this.map);

        // Add layer controls
        const overlays = {
            'Area Kecamatan': this.kecamatanLayer,
            'Lokasi Puskesmas': this.puskesmasLayer
        };
        
        L.control.layers(null, overlays).addTo(this.map);
        
        this.loadLayers();
        this.addLegend();
    },

    getColor(satisfaction) {
        return satisfaction > 90 ? '#006837' :
               satisfaction > 80 ? '#1a9850' :
               satisfaction > 70 ? '#66bd63' :
               satisfaction > 60 ? '#fd8d3c' :
                                 '#d73027';
    },

    calculateKecamatanCenter(puskesmasList) {
        const lats = puskesmasList.map(p => p.coords[0]);
        const lngs = puskesmasList.map(p => p.coords[1]);
        return [
            lats.reduce((a, b) => a + b) / lats.length,
            lngs.reduce((a, b) => a + b) / lngs.length
        ];
    },

    loadLayers() {
        const kecamatanGroups = {};
        const puskesmasData = <?php echo json_encode($puskesmasData); ?>;
        
        // Group puskesmas by kecamatan
        puskesmasData.forEach(puskesmas => {
            if (!kecamatanGroups[puskesmas.kecamatan]) {
                kecamatanGroups[puskesmas.kecamatan] = {
                    puskesmas: [],
                    totalSatisfaction: 0,
                    count: 0
                };
            }
            kecamatanGroups[puskesmas.kecamatan].puskesmas.push(puskesmas);
            kecamatanGroups[puskesmas.kecamatan].totalSatisfaction += parseFloat(puskesmas.satisfaction);
            kecamatanGroups[puskesmas.kecamatan].count++;
        });

        // Create circles for each kecamatan
        Object.entries(kecamatanGroups).forEach(([kecamatan, data]) => {
            const center = this.calculateKecamatanCenter(data.puskesmas);
            const avgSatisfaction = data.totalSatisfaction / data.count;
            
            // Create kecamatan circle
            const circle = L.circle(center, {
                color: this.getColor(avgSatisfaction),
                fillColor: this.getColor(avgSatisfaction),
                fillOpacity: 0.2,
                weight: 2,
                radius: 1500 // 1.5km radius
            }).addTo(this.kecamatanLayer);

            // Add kecamatan popup
            circle.bindPopup(`
                <div class="popup-content">
                    <h5>${kecamatan}</h5>
                    <p>Rata-rata Kepuasan: ${avgSatisfaction.toFixed(1)}%</p>
                    <p>Jumlah Puskesmas: ${data.count}</p>
                </div>
            `);

            // Add puskesmas markers
            data.puskesmas.forEach(puskesmas => {
                const marker = L.marker(puskesmas.coords, {
                    title: puskesmas.name
                }).addTo(this.puskesmasLayer);

                marker.bindPopup(`
                    <div class="popup-content">
                        <h5>${puskesmas.name}</h5>
                        <p>Tingkat Kepuasan: ${puskesmas.satisfaction}%</p>
                        <p>Kecamatan: ${puskesmas.kecamatan}</p>
                        <p>Kelurahan: ${puskesmas.kelurahan}</p>
                        <p>Update: ${puskesmas.tanggal_update}</p>
                        <p>Responden: ${puskesmas.jumlah_responden}</p>
                    </div>
                `);
            });
        });
    },

    addLegend() {
        const legend = L.control({ position: 'bottomright' });
        legend.onAdd = () => {
            const div = L.DomUtil.create('div', 'legend');
            const grades = [90, 80, 70, 60, 0];
            
            div.innerHTML = '<h4>Tingkat Kepuasan</h4>';
            grades.forEach((grade, index) => {
                div.innerHTML += `
                    <i style="background:${this.getColor(grade + 1)}"></i>
                    ${grade}${grades[index + 1] ? '&ndash;' + grades[index + 1] : '+'}%<br>
                `;
            });
            return div;
        };
        legend.addTo(this.map);
    }
};

// Initialize the map
MapManager.init();
</script>
    <script src="https://unpkg.com/opencage-api-client"></script>
</body>
</html>