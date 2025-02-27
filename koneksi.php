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
        // Convert to standard format
        $coord = str_replace(['-', ' '], '', $coord); // Remove negative and spaces
        $coord = str_replace(',', '.', $coord); // Replace comma with dot
        
        // Handle different coordinate formats
        if (strpos($coord, '.') !== false) {
            $parts = explode('.', $coord);
            if (count($parts) > 2) {
                // Handle format like 110.4276
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

            // Parse CSV data
            $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
            $headers = array_shift($rows); // Get and remove headers

            // Clean and validate data
            $this->data = array_map(function($row) use ($headers) {
                if (count($row) !== count($headers)) {
                    return null;
                }

                $data = array_combine($headers, $row);
                
                // Clean coordinates
                if (isset($data['latitude'], $data['longitude'])) {
                    $data['latitude'] = $this->cleanCoordinate($data['latitude']);
                    $data['longitude'] = $this->cleanCoordinate($data['longitude']);
                }

                return $data;
            }, $rows);

            // Remove null entries
            $this->data = array_filter($this->data);
            
            return $this->data;

        } catch (Exception $e) {
            error_log("Error fetching data: " . $e->getMessage());
            return [];
        }
    }

    public function getPuskesmasData() {
        if (!$this->data) {
            $this->fetchData();
        }

        $puskesmasData = [];
        foreach ($this->data as $row) {
            if (isset($row['nama_puskesmas'], $row['latitude'], $row['longitude'], 
                     $row['tingkat_kepuasan'], $row['kecamatan'], $row['kelurahan'],
                     $row['tanggal_update'], $row['jumlah_responden'], $row['keterangan'])) {
                
                try {
                    $puskesmasData[] = [
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
                } catch (Exception $e) {
                    error_log("Error processing row: " . json_encode($row));
                    continue;
                }
            }
        }
        return $puskesmasData;
    }

    public function getKecamatanData() {
        if (!$this->data) {
            $this->fetchData();
        }

        try {
            $geojsonPath = __DIR__ . '/file.geojson';
            if (!file_exists($geojsonPath)) {
                throw new Exception("GeoJSON file not found");
            }
            return json_decode(file_get_contents($geojsonPath), true);
        } catch (Exception $e) {
            error_log("Error loading GeoJSON: " . $e->getMessage());
            return null;
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
}
?>