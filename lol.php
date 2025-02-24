<?php
require_once 'koneksi.php';  // Add this line to include the class definition

$db = new DatabaseConnection();
$puskesmasData = $db->getPuskesmasData();
$kepuasanData = $db->getKepuasanByKecamatan();

echo "<pre>";
print_r($puskesmasData);
print_r($kepuasanData);
echo "</pre>";