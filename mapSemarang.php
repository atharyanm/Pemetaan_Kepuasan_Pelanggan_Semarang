<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisasi Kepuasan Puskesmas</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
    <div id="map" style="height: 500px;"></div>

    <script>
        var map = L.map('map').setView([-6.966667, 110.416664], 12);
        
        L.tileLayer('https://api.maptiler.com/maps/streets-v2/{z}/{x}/{y}.png?key=4ANseDQ5KBR4CR9HploX', {
            attribution: 'Map data &copy; OpenStreetMap contributors'
        }).addTo(map);

        const puskesmasData = [
            // Semarang Tengah
            { name: "PONCOL", coords: [-6.9697, 110.4276], satisfaction: 85, kecamatan: "Semarang Tengah", kelurahan: "Poncol" },
            { name: "MIROTO", coords: [-6.9832, 110.4123], satisfaction: 82, kecamatan: "Semarang Tengah", kelurahan: "Miroto" },
            { name: "PANDANARAN", coords: [-6.9847, 110.4088], satisfaction: 83, kecamatan: "Semarang Tengah", kelurahan: "Pandanaran" },
            
            // Semarang Utara
            { name: "BANDARHARJO", coords: [-6.9644, 110.4097], satisfaction: 80, kecamatan: "Semarang Utara", kelurahan: "Bandarharjo" },
            { name: "BULU LOR", coords: [-6.9683, 110.4198], satisfaction: 81, kecamatan: "Semarang Utara", kelurahan: "Bulu Lor" },
            
            // Semarang Timur
            { name: "HALMAHERA", coords: [-6.9789, 110.4289], satisfaction: 84, kecamatan: "Semarang Timur", kelurahan: "Rejosari" },
            { name: "BUGANGAN", coords: [-6.9825, 110.4375], satisfaction: 82, kecamatan: "Semarang Timur", kelurahan: "Bugangan" },
            { name: "KARANGDORO", coords: [-6.9736, 110.4321], satisfaction: 83, kecamatan: "Semarang Timur", kelurahan: "Karangdoro" },
            
            // Semarang Selatan
            { name: "LAMPER TENGAH", coords: [-6.9989, 110.4356], satisfaction: 85, kecamatan: "Semarang Selatan", kelurahan: "Lamper Tengah" },
            
            // Semarang Barat
            { name: "KARANGAYU", coords: [-6.9831, 110.3892], satisfaction: 84, kecamatan: "Semarang Barat", kelurahan: "Karangayu" },
            { name: "LEBDOSARI", coords: [-6.9897, 110.3831], satisfaction: 82, kecamatan: "Semarang Barat", kelurahan: "Lebdosari" },
            { name: "MANYARAN", coords: [-7.0025, 110.3897], satisfaction: 83, kecamatan: "Semarang Barat", kelurahan: "Manyaran" },
            { name: "KROBOKAN", coords: [-6.9789, 110.3975], satisfaction: 81, kecamatan: "Semarang Barat", kelurahan: "Krobokan" },
            { name: "NGEMPLAK SIMONGAN", coords: [-6.9972, 110.4011], satisfaction: 82, kecamatan: "Semarang Barat", kelurahan: "Ngemplak Simongan" },
            
            // Gayamsari
            { name: "GAYAMSARI", coords: [-6.9936, 110.4453], satisfaction: 84, kecamatan: "Gayamsari", kelurahan: "Gayamsari" },
            { name: "CANDILAMA", coords: [-6.9889, 110.4397], satisfaction: 83, kecamatan: "Gayamsari", kelurahan: "Candilama" },
            
            // Candisari
            { name: "KAGOK", coords: [-7.0069, 110.4214], satisfaction: 85, kecamatan: "Candisari", kelurahan: "Kagok" },
            
            // Gajahmungkur
            { name: "PEGANDAN", coords: [-7.0153, 110.4108], satisfaction: 82, kecamatan: "Gajahmungkur", kelurahan: "Pegandan" },
            
            // Genuk
            { name: "GENUK", coords: [-6.9611, 110.4733], satisfaction: 81, kecamatan: "Genuk", kelurahan: "Genuk" },
            { name: "BANGETAYU", coords: [-6.9733, 110.4789], satisfaction: 82, kecamatan: "Genuk", kelurahan: "Bangetayu" },
            
            // Pedurungan
            { name: "TLOGOSARI WETAN", coords: [-6.9897, 110.4678], satisfaction: 83, kecamatan: "Pedurungan", kelurahan: "Tlogosari Wetan" },
            { name: "TLOGOSARI KULON", coords: [-6.9933, 110.4589], satisfaction: 84, kecamatan: "Pedurungan", kelurahan: "Tlogosari Kulon" },
            
            // Kedungmundu
            { name: "KEDUNGMUNDU", coords: [-7.0253, 110.4589], satisfaction: 85, kecamatan: "Tembalang", kelurahan: "Kedungmundu" },
            
            // Rowosari
            { name: "ROWOSARI", coords: [-7.0397, 110.4853], satisfaction: 81, kecamatan: "Tembalang", kelurahan: "Rowosari" },
            
            // Banyumanik
            { name: "NGESREP", coords: [-7.0397, 110.4214], satisfaction: 84, kecamatan: "Banyumanik", kelurahan: "Ngesrep" },
            { name: "PADANGSARI", coords: [-7.0547, 110.4219], satisfaction: 83, kecamatan: "Banyumanik", kelurahan: "Padangsari" },
            { name: "SRONDOL", coords: [-7.0644, 110.4156], satisfaction: 85, kecamatan: "Banyumanik", kelurahan: "Srondol" },
            { name: "PUDAKPAYUNG", coords: [-7.0847, 110.4178], satisfaction: 82, kecamatan: "Banyumanik", kelurahan: "Pudakpayung" },
            
            // Gunungpati
            { name: "GUNUNGPATI", coords: [-7.0847, 110.3953], satisfaction: 83, kecamatan: "Gunungpati", kelurahan: "Gunungpati" },
            { name: "SEKARAN", coords: [-7.0514, 110.3897], satisfaction: 84, kecamatan: "Gunungpati", kelurahan: "Sekaran" },
            
            // Mijen
            { name: "MIJEN", coords: [-7.0397, 110.3314], satisfaction: 82, kecamatan: "Mijen", kelurahan: "Mijen" },
            
            // Ngaliyan
            { name: "KARANGMALANG", coords: [-6.9972, 110.3553], satisfaction: 83, kecamatan: "Ngaliyan", kelurahan: "Karangmalang" },
            { name: "TAMBAKAJI", coords: [-6.9897, 110.3475], satisfaction: 84, kecamatan: "Ngaliyan", kelurahan: "Tambakaji" },
            { name: "PURWOYOSO", coords: [-6.9936, 110.3639], satisfaction: 82, kecamatan: "Ngaliyan", kelurahan: "Purwoyoso" },
            { name: "NGALIYAN", coords: [-7.0025, 110.3511], satisfaction: 85, kecamatan: "Ngaliyan", kelurahan: "Ngaliyan" },
            
            // Tugu
            { name: "MANGKANG", coords: [-6.9697, 110.3153], satisfaction: 81, kecamatan: "Tugu", kelurahan: "Mangkang" },
            { name: "KARANGANYAR", coords: [-6.9736, 110.3275], satisfaction: 82, kecamatan: "Tugu", kelurahan: "Karanganyar" },
            
            // Additional
            { name: "PLAMONGANSARI", coords: [-6.9789, 110.4853], satisfaction: 83, kecamatan: "Pedurungan", kelurahan: "Plamongansari" },
            { name: "BULUSAN", coords: [-7.0514, 110.4442], satisfaction: 84, kecamatan: "Tembalang", kelurahan: "Bulusan" }
        ];

        const kecamatanData = [
            { 
                name: "Semarang Tengah", 
                coordinates: [[-6.98, 110.40], [-6.95, 110.43], [-6.97, 110.44], [-6.98, 110.40]]
            },
            { 
                name: "Semarang Utara", 
                coordinates: [[-6.95, 110.40], [-6.93, 110.43], [-6.96, 110.44], [-6.95, 110.40]]
            },
            { 
                name: "Semarang Timur", 
                coordinates: [[-6.97, 110.42], [-6.95, 110.45], [-6.99, 110.44], [-6.97, 110.42]]
            },
            { 
                name: "Semarang Selatan", 
                coordinates: [[-7.00, 110.41], [-6.98, 110.44], [-7.02, 110.43], [-7.00, 110.41]]
            },
            { 
                name: "Semarang Barat", 
                coordinates: [[-6.98, 110.37], [-6.95, 110.40], [-7.01, 110.39], [-6.98, 110.37]]
            },
            { 
                name: "Gayamsari", 
                coordinates: [[-6.98, 110.44], [-6.96, 110.46], [-7.00, 110.45], [-6.98, 110.44]]
            },
            { 
                name: "Candisari", 
                coordinates: [[-7.00, 110.41], [-6.98, 110.43], [-7.02, 110.42], [-7.00, 110.41]]
            },
            { 
                name: "Gajahmungkur", 
                coordinates: [[-7.01, 110.40], [-6.99, 110.42], [-7.03, 110.41], [-7.01, 110.40]]
            },
            { 
                name: "Genuk", 
                coordinates: [[-6.95, 110.46], [-6.93, 110.49], [-6.97, 110.48], [-6.95, 110.46]]
            },
            { 
                name: "Pedurungan", 
                coordinates: [[-6.98, 110.45], [-6.96, 110.48], [-7.00, 110.47], [-6.98, 110.45]]
            },
            { 
                name: "Tembalang", 
                coordinates: [[-7.02, 110.43], [-7.00, 110.46], [-7.04, 110.45], [-7.02, 110.43]]
            },
            { 
                name: "Banyumanik", 
                coordinates: [[-7.03, 110.40], [-7.01, 110.43], [-7.05, 110.42], [-7.03, 110.40]]
            },
            { 
                name: "Gunungpati", 
                coordinates: [[-7.08, 110.35], [-7.05, 110.38], [-7.09, 110.37], [-7.08, 110.35]]
            },
            { 
                name: "Mijen", 
                coordinates: [[-7.03, 110.31], [-7.00, 110.34], [-7.04, 110.33], [-7.03, 110.31]]
            },
            { 
                name: "Ngaliyan", 
                coordinates: [[-6.99, 110.33], [-6.96, 110.36], [-7.00, 110.35], [-6.99, 110.33]]
            },
            { 
                name: "Tugu", 
                coordinates: [[-6.96, 110.30], [-6.93, 110.33], [-6.97, 110.32], [-6.96, 110.30]]
            }
        ];

        function calculateAverages() {
            // Calculate Kelurahan averages from Puskesmas data
            const kelurahanAverages = {};
            const kelurahanCounts = {};
            
            puskesmasData.forEach(puskesmas => {
                if (!kelurahanAverages[puskesmas.kelurahan]) {
                    kelurahanAverages[puskesmas.kelurahan] = 0;
                    kelurahanCounts[puskesmas.kelurahan] = 0;
                }
                kelurahanAverages[puskesmas.kelurahan] += puskesmas.satisfaction;
                kelurahanCounts[puskesmas.kelurahan]++;
            });

            const kelurahanSatisfaction = {};
            for (let kel in kelurahanAverages) {
                kelurahanSatisfaction[kel] = Math.round(kelurahanAverages[kel] / kelurahanCounts[kel]);
            }

            // Calculate Kecamatan averages from Kelurahan averages
            const kecamatanAverages = {};
            const kecamatanCounts = {};
            
            puskesmasData.forEach(puskesmas => {
                if (!kecamatanAverages[puskesmas.kecamatan]) {
                    kecamatanAverages[puskesmas.kecamatan] = 0;
                    kecamatanCounts[puskesmas.kecamatan] = 0;
                }
                kecamatanAverages[puskesmas.kecamatan] += puskesmas.satisfaction;
                kecamatanCounts[puskesmas.kecamatan]++;
            });

            const kecamatanSatisfaction = {};
            for (let kec in kecamatanAverages) {
                kecamatanSatisfaction[kec] = Math.round(kecamatanAverages[kec] / kecamatanCounts[kec]);
            }

            return { kelurahanSatisfaction, kecamatanSatisfaction };
        }

        function getColor(satisfaction) {
            return satisfaction > 90 ? '#006837' :
                   satisfaction > 80 ? '#1a9850' :
                   satisfaction > 70 ? '#66bd63' :
                   satisfaction > 60 ? '#fd8d3c' :
                                      '#d73027';
        }

        function clearMap() {
            map.eachLayer((layer) => {
                if (!(layer instanceof L.TileLayer)) {
                    map.removeLayer(layer);
                }
            });
        }

        // Calculate averages once when page loads
        const { kelurahanSatisfaction, kecamatanSatisfaction } = calculateAverages();

        function showKecamatanLayer() {
            clearMap();
            kecamatanData.forEach(kecamatan => {
                const satisfaction = kecamatanSatisfaction[kecamatan.name] || 0;
                L.polygon(kecamatan.coordinates, {
                    color: getColor(satisfaction),
                    fillOpacity: 0.7
                }).bindPopup(`
                    <b>Kecamatan ${kecamatan.name}</b><br>
                    Rata-rata Kepuasan: ${satisfaction}%
                `).addTo(map);
            });
        }

        function showKelurahanLayer() {
            clearMap();
            puskesmasData.forEach(puskesmas => {
                const satisfaction = kelurahanSatisfaction[puskesmas.kelurahan] || 0;
                if (satisfaction > 0) {
                    L.circleMarker(puskesmas.coords, { 
                        color: getColor(satisfaction), 
                        radius: 10, 
                        fillOpacity: 0.7 
                    }).bindPopup(`
                        <b>Kelurahan ${puskesmas.kelurahan}</b><br>
                        Rata-rata Kepuasan: ${satisfaction}%
                    `).addTo(map);
                }
            });
        }

        function showPuskesmasMarkers() {
            clearMap();
            puskesmasData.forEach(puskesmas => {
                L.marker(puskesmas.coords)
                    .bindPopup(`
                        <b>${puskesmas.name}</b><br>
                        Tingkat Kepuasan: ${puskesmas.satisfaction}%<br>
                        Kecamatan: ${puskesmas.kecamatan}<br>
                        Kelurahan: ${puskesmas.kelurahan}
                    `)
                    .addTo(map);
            });
        }

        map.on('zoomend', function() {
            const zoom = map.getZoom();
            if (zoom < 12) {
                showKecamatanLayer();
            } else if (zoom < 14) {
                showKelurahanLayer();
            } else {
                showPuskesmasMarkers();
            }
        });

        showKecamatanLayer();
    </script>
</body>
</html>
