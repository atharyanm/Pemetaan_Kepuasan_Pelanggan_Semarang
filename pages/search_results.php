<?php
// Debug untuk memeriksa data yang diterima
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if required data is available
if (!isset($groupedData)) {
    echo '<div class="alert alert-danger">Data tidak tersedia</div>';
    exit;
}

// Helper functions
function getValue($array, $key, $default = '') {
    return isset($array[$key]) ? $array[$key] : $default;
}

function formatCoordinate($coord) {
    return number_format((float)$coord, 6, '.', '');
}

// Display results
if (empty($groupedData)) {
    ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Tidak ada data puskesmas yang ditemukan.
    </div>
    <?php
} else {
    foreach ($groupedData as $kecamatan => $puskesmasList) {
        ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-hospital-alt"></i> 
                    Kecamatan <?php echo htmlspecialchars($kecamatan); ?>
                    <div class="float-end ms-2">
                        <span class="badge bg-secondary">
                            <?php echo count($puskesmasList); ?> Puskesmas
                        </span>
                    </div>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3" width="5%">No</th>
                                <th class="px-3" width="20%">Nama Puskesmas</th>
                                <th class="px-3" width="15%">Koordinat</th>
                                <th class="px-3" width="10%">Kepuasan</th>
                                <th class="px-3" width="15%">Kelurahan</th>
                                <th class="px-3" width="15%">Update Terakhir</th>
                                <th class="px-3" width="10%">Responden</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($puskesmasList as $puskesmas): 
                                // Debug data
                                // echo '<pre>'; print_r($puskesmas); echo '</pre>';
                                
                                // Get values safely with correct keys - sesuaikan dengan struktur data dari koneksi.php
                                $nama_puskesmas = getValue($puskesmas, 'name', 'N/A');
                                $coordinates = getValue($puskesmas, 'coords', [0, 0]);
                                $latitude = formatCoordinate($coordinates[0]);
                                $longitude = formatCoordinate($coordinates[1]);
                                $tingkat_kepuasan = floatval(getValue($puskesmas, 'satisfaction', 0));
                                $kelurahan = getValue($puskesmas, 'kelurahan', 'N/A');
                                $tanggal_update = getValue($puskesmas, 'tanggal_update', date('Y-m-d'));
                                $jumlah_responden = intval(getValue($puskesmas, 'jumlah_responden', 0));

                                // Calculate satisfaction percentage
                                $satisfactionPercent = number_format($tingkat_kepuasan, 1);
                                
                                // Determine satisfaction class and icon
                                $satisfactionClass = '';
                                $satisfactionIcon = '';
                                if ($tingkat_kepuasan >= 80) {
                                    $satisfactionClass = 'text-success';
                                    $satisfactionIcon = 'fa-smile';
                                } elseif ($tingkat_kepuasan >= 60) {
                                    $satisfactionClass = 'text-warning';
                                    $satisfactionIcon = 'fa-meh';
                                } else {
                                    $satisfactionClass = 'text-danger';
                                    $satisfactionIcon = 'fa-frown';
                                }
                            ?>
                            <tr>
                                <td class="px-3"><?php echo $no++; ?></td>
                                <td class="px-3 fw-bold"><?php echo htmlspecialchars($nama_puskesmas); ?></td>
                                <td class="px-3">
                                    <small>
                                        <i class="fas fa-map-marker-alt text-danger"></i>
                                        <?php echo htmlspecialchars("$latitude, $longitude"); ?>
                                    </small>
                                </td>
                                <td class="px-3">
                                    <span class="<?php echo $satisfactionClass; ?>">
                                        <i class="far <?php echo $satisfactionIcon; ?>"></i>
                                        <?php echo $satisfactionPercent; ?>%
                                    </span>
                                </td>
                                <td class="px-3"><?php echo htmlspecialchars($kelurahan); ?></td>
                                <td class="px-3">
                                    <small>
                                        <i class="far fa-calendar-alt"></i>
                                        <?php echo date('d/m/Y', strtotime($tanggal_update)); ?>
                                    </small>
                                </td>
                                <td class="px-3 text-center">
                                    <span class="badge bg-info">
                                        <i class="fas fa-users"></i>
                                        <?php echo number_format($jumlah_responden); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
}
?>