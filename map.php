<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Puskesmas Kota Semarang</title>
    <style>
        #map { 
            height: calc(100vh - 100px); 
            width: 100%; 
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .legend {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255,255,255,0.8);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 5px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
</head>
<body>
    <div id="map"></div>

    <script>
        // Data Puskesmas
        const puskesmasData = [
            { name: "Puskesmas Bandarharjo", lat: -6.9622, lng: 110.4215, kecamatan: "Semarang Utara", alamat: "Jl. Layur, Dadapsari" },
            { name: "Puskesmas Banget Ayu", lat: -6.9832, lng: 110.4766, kecamatan: "Genuk", alamat: "Jl. Raya Bangetayu" },
            { name: "Puskesmas Bugangan", lat: -6.9741, lng: 110.4402, kecamatan: "Semarang Timur", alamat: "Jl. Cilosari" },
            { name: "Puskesmas Bulu Lor", lat: -7.0332, lng: 110.4421, kecamatan: "Semarang Utara", alamat: "Jl. Bonowati Selatan II" },
            { name: "Puskesmas Candilama", lat: -7.0134, lng: 110.4318, kecamatan: "Candisari", alamat: "Jl. Dr. Wahidin 22" },
            { name: "Puskesmas Gayamsari", lat: -6.9965, lng: 110.4492, kecamatan: "Gayamsari", alamat: "Jl. Slamet Riyadi 4A" },
            { name: "Puskesmas Genuk", lat: -6.9666, lng: 110.4700, kecamatan: "Genuk", alamat: "Jl. Genuksari Raya" },
            { name: "Puskesmas Gunung Pati", lat: -7.0940, lng: 110.3343, kecamatan: "Gunung Pati", alamat: "Jl. Mr. Wuryanto No.38" },
            { name: "Puskesmas Halmahera", lat: -6.9948, lng: 110.4377, kecamatan: "Semarang Timur", alamat: "Jl. Halmahera Raya 38" },
            { name: "Puskesmas Kagok", lat: -7.0086, lng: 110.4172, kecamatan: "Candisari", alamat: "Jl. Telomoyo 3" },
            { name: "Puskesmas Karang Anyar", lat: -6.9710, lng: 110.3342, kecamatan: "Tugu", alamat: "Jl. Karang Anyar 29 E" },
            { name: "Puskesmas Karang Ayu", lat: -6.9805, lng: 110.3928, kecamatan: "Semarang Barat", alamat: "Jl. Kencowungu III/28" },
            { name: "Puskesmas Karang Doro", lat: -6.9731, lng: 110.4379, kecamatan: "Semarang Timur", alamat: "Jl. Raden Patah 178" },
            { name: "Puskesmas Karang Malang", lat: -7.0946, lng: 110.3344, kecamatan: "Mijen", alamat: "Jl. RM. Soebagiono" },
            { name: "Puskesmas Kedung Mundu", lat: -7.0246, lng: 110.4574, kecamatan: "Tembalang", alamat: "Jl. Sambiroto 1" },
            { name: "Puskesmas Krobokan", lat: -6.9870, lng: 110.3914, kecamatan: "Semarang Barat", alamat: "Jl. Ari Buana I/XIII" },
            { name: "Puskesmas Lamper Tengah", lat: -6.9980, lng: 110.4364, kecamatan: "Semarang Selatan", alamat: "Jl. Lamper Tengah Gg.XV" },
            { name: "Puskesmas Lebdosari", lat: -6.9942, lng: 110.3797, kecamatan: "Semarang Barat", alamat: "Jl. Taman Lebdosari" },
            { name: "Puskesmas Mangkang", lat: -6.9736, lng: 110.2976, kecamatan: "Tugu", alamat: "Jl. Jendral Oerip Soemoharjo KM 16" },
            { name: "Puskesmas Manyaran", lat: -6.9875, lng: 110.4049, kecamatan: "Semarang Barat", alamat: "Jl. Abdulrahman Saleh 267" },
            { name: "Puskesmas Mijen", lat: -7.0563, lng: 110.3141, kecamatan: "Mijen", alamat: "Jl. RM. Hadi Soebeno" },
            { name: "Puskesmas Miroto", lat: -6.9745, lng: 110.3998, kecamatan: "Semarang Tengah", alamat: "Jl. Taman Seteran Barat No. 03" },
            { name: "Puskesmas Ngaliyan", lat: -6.9977, lng: 110.3462, kecamatan: "Ngaliyan", alamat: "Jl. Wismasari Raya" },
            { name: "Puskesmas Ngemplak Simongan", lat: -7.0013, lng: 110.3949, kecamatan: "Semarang Barat", alamat: "Jl. Srinindito IV" },
            { name: "Puskesmas Ngesrep", lat: -7.0463, lng: 110.4179, kecamatan: "Banyumanik", alamat: "Jl. Teuku Umar 271" },
            { name: "Puskesmas Padangsari", lat: -7.0706, lng: 110.4217, kecamatan: "Banyumanik", alamat: "Jl. Meranti Raya 389" },
            { name: "Puskesmas Pandanaran", lat: -6.9877, lng: 110.4146, kecamatan: "Semarang Selatan", alamat: "Jl. Pandanaran 79" },
            { name: "Puskesmas Pegandan", lat: -7.0113, lng: 110.4048, kecamatan: "Gajah Mungkur", alamat: "Jl. Kendeng Barat III/2" },
            { name: "Puskesmas Poncol", lat: -6.9794, lng: 110.4117, kecamatan: "Semarang Tengah", alamat: "Jl. Imam Bonjol 114" },
            { name: "Puskesmas Pundakpayung", lat: -7.0969, lng: 110.4097, kecamatan: "Banyumanik", alamat: "Jl. Payungmas Raya" },
            { name: "Puskesmas Purwoyoso", lat: -7.0151, lng: 110.3550, kecamatan: "Ngaliyan", alamat: "Jl. Siliwangi No 527" },
            { name: "Puskesmas Rowosari", lat: -7.0606, lng: 110.4810, kecamatan: "Tembalang", alamat: "Jl Prof Soeharso, Rowosari" },
            { name: "Puskesmas Sekaran", lat: -7.0303, lng: 110.3724, kecamatan: "Gunung Pati", alamat: "Jl. Raya Sekaran" },
            { name: "Puskesmas Srondol", lat: -7.0589, lng: 110.4141, kecamatan: "Banyumanik", alamat: "Jl. Setiabudi No.209" },
            { name: "Puskesmas Tambak Aji", lat: -6.9833, lng: 110.3504, kecamatan: "Ngaliyan", alamat: "Jl. Raya Wahsongo" },
            { name: "Puskesmas Tlogosari Kulon", lat: -6.9808, lng: 110.4575, kecamatan: "Pedurungan", alamat: "Jl. Taman Satrio Manah 2" },
            { name: "Puskesmas Tlogosari Wetan", lat: -6.9805, lng: 110.4591, kecamatan: "Pedurungan", alamat: "Jl. Soekarno-Hatta" },
            { name: "Puskesmas Bulusan", lat: -7.053155, lng: 110.456356, kecamatan: "Tembalang", alamat: "Jl. Timoho Raya" },
            { name: "Puskesmas Plamongansari", lat: -7.024000610502623, lng: 110.48689731091548, kecamatan: "Pedurungan", alamat: "Jl. Plamongansari V No.57" }
        ];
        console.log("Kecamatan dari GeoJSON:", feature.properties.name);

        // Initialize map
        const map = L.map('map').setView([-7.0051, 110.4381], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap contributors' }).addTo(map);

const markers = L.markerClusterGroup(); // Pindahkan deklarasi di luar fetch

fetch('https://gist.githubusercontent.com/lintangtimur/43059dd4b146cbe7bd366cfb4b7fc783/raw/semarang_boundary.geojson')
    .then(response => response.json())
    .then(data => {
        L.geoJSON(data, {
            style: () => ({
                fillColor: getRandomColor(),
                weight: 2, color: 'white', fillOpacity: 0.5
            }),
            onEachFeature: (feature, layer) => {
                console.log("Kecamatan dari GeoJSON:", feature.properties.name);
                layer.bindPopup(`<b>${feature.properties.name}</b><br>Jumlah Puskesmas: ${countPuskesmasInKecamatan(feature.properties.name)}`);
            }
        }).addTo(map);

        puskesmasData.forEach(pus => {
            const marker = L.marker([pus.lat, pus.lng])
                .bindPopup(`<b>${pus.name}</b><br>Kecamatan: ${pus.kecamatan}<br>Alamat: ${pus.alamat}`);
            markers.addLayer(marker);
        });

        map.addLayer(markers);
    });

// Pastikan fitur tambahan dimuat setelah Leaflet di-import
const searchControl = new L.Control.Search({
    position: 'topright',
    layer: markers,
    propertyName: 'name',
    marker: false,
    moveToLocation: function(latlng) { map.setView(latlng, 16); }
});
map.addControl(searchControl);

L.control.locate({ position: 'topright', strings: { title: "Tampilkan lokasi saya" } }).addTo(map);

    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</body>
</html>