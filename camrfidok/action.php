<?php
require_once("./config/db.php");

$qry = "SELECT * FROM rfid_code WHERE used=0 ORDER BY id DESC LIMIT 1";
$query = mysqli_query($koneksi, $qry);

// Periksa apakah hasil query ada
if ($query && mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_array($query);
    $rfid_code = isset($row['rfid_code']) ? $row['rfid_code'] : ''; // Akses aman
} else {
    $rfid_code = ''; // Set default jika tidak ada data
}

// Jika parameter 'do' adalah 'get_rfid_code', tampilkan rfid_code
if (isset($_GET['do']) && $_GET['do'] == 'get_rfid_code') {
    echo $rfid_code;
}
?>
