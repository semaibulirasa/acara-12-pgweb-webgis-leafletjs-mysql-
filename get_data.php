<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "db_bulky_waste";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "error" => "Koneksi ke basis data gagal: " . $conn->connect_error
    ]);
    exit;
}

$conn->set_charset("utf8mb4");

$sql = "SELECT id, nama_object, alamat, latitude, longitude, kelurahan, kecamatan, kota FROM bulky_waste ORDER BY id ASC";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        "error" => "Query gagal dijalankan: " . $conn->error
    ]);
    $conn->close();
    exit;
}

$locations = [];

while ($row = $result->fetch_assoc()) {
    $locations[] = [
        'id'          => (int) $row['id'],
        'nama_object' => $row['nama_object'],
        'alamat'      => $row['alamat'],
        'latitude'    => (float) $row['latitude'],
        'longitude'   => (float) $row['longitude'],
        'kelurahan'   => $row['kelurahan'],
        'kecamatan'   => $row['kecamatan'],
        'kota'        => $row['kota']
    ];
}

echo json_encode($locations, JSON_UNESCAPED_UNICODE);

$conn->close();
?>