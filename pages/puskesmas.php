<?php
session_start();
require_once '../koneksi.php';

// Initialize database and search variables
$db = new DatabaseConnection();
$puskesmasData = $db->getPuskesmasData();
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';

// Handle AJAX search request
if (isset($_GET['ajax_search'])) {
    try {
        // Filter data berdasarkan pencarian
        $filteredData = [];
        $searchKeyword = strtolower($_GET['search']);
        
        foreach ($puskesmasData as $puskesmas) {
            if (
                stripos(strtolower($puskesmas['name']), $searchKeyword) !== false ||
                stripos(strtolower($puskesmas['kelurahan']), $searchKeyword) !== false ||
                stripos(strtolower($puskesmas['kecamatan']), $searchKeyword) !== false
            ) {
                $filteredData[] = $puskesmas;
            }
        }

        // Group data berdasarkan kecamatan
        $groupedData = [];
        foreach ($filteredData as $puskesmas) {
            if (isset($puskesmas['kecamatan'])) {
                $groupedData[$puskesmas['kecamatan']][] = $puskesmas;
            }
        }

        // Sort kecamatan alphabetically
        ksort($groupedData);

        // Return search results
        include 'search_results.php';
        exit;
        
    } catch (Exception $e) {
        http_response_code(500);
        echo '<div class="alert alert-danger">Terjadi kesalahan: ' . htmlspecialchars($e->getMessage()) . '</div>';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Puskesmas - SIG Kepuasan Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Daftar Puskesmas Kota Semarang</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="" id="searchForm" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" id="searchInput" class="form-control form-control-lg"
                               placeholder="Cari Puskesmas, Kelurahan, atau Kecamatan..."
                               value="<?php echo htmlspecialchars($searchKeyword); ?>"
                               autocomplete="off">
                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <button type="button" class="btn btn-secondary btn-lg" id="resetSearch">
                            <i class="fas fa-times"></i> Reset
                        </button>
                    </div>
                </form>
                
                <!-- Search Results Table (Initially Hidden) -->
                <div id="searchResults" style="display: none;"></div>

                <!-- Default Data Table -->
                <div id="defaultData">
                    <?php include 'default_table.php'; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        const searchForm = $('#searchForm');
        const searchInput = $('#searchInput');
        const searchResults = $('#searchResults');
        const defaultData = $('#defaultData');
        const resetButton = $('#resetSearch');

        // Prevent form from submitting normally
        searchForm.on('submit', function(e) {
            e.preventDefault();
            fetchResults();
        });

        resetButton.on('click', function() {
            searchInput.val('');
            searchResults.hide();
            defaultData.show();
        });

        function fetchResults() {
            const searchTerm = searchInput.val().trim();
            
            if (searchTerm === '') {
                searchResults.hide();
                defaultData.show();
                return;
            }

            // Show loading indicator
            searchResults.html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
            searchResults.show();
            defaultData.hide();

            // Make AJAX request
            $.ajax({
                url: 'pages/puskesmas.php',
                method: 'GET',
                data: { 
                    search: searchTerm, 
                    ajax_search: true 
                },
                success: function(response) {
                    if (response.trim() === '') {
                        searchResults.html('<div class="alert alert-info"><i class="fas fa-info-circle"></i> Tidak ada hasil yang ditemukan.</div>');
                    } else {
                        searchResults.html(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ajax Error:', status, error);
                    showError();
                }
            });
        }

        function showError() {
            searchResults.html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> 
                    Terjadi kesalahan saat mengambil data. 
                    <button type="button" class="btn btn-sm btn-danger ms-2" onclick="fetchResults()">
                        <i class="fas fa-sync"></i> Coba Lagi
                    </button>
                </div>
            `);
        }

        // Add search on keyup with debounce
        let searchTimeout;
        searchInput.on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(fetchResults, 500);
        });
    });
    </script>
</body>
</html>