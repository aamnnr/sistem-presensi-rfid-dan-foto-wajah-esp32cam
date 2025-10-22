<?php
session_start();
require_once("./config/db.php");
require_once("./config/function.php");

if (!$_SESSION['username']) {
    die(json_encode(array(
        'status' => false, 
        'message' => 'Session kamu berakhir, Silahkan login ulang.'
    )));
}

if (isset($_POST)) {
    /// Validasi post name yang kosong
    $kelasID = $_POST['kelas_id'];
    if (is_numeric($kelasID) == FALSE) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Kelas ID Tidak Valid!.'
        )));
    }

    $sql = "SELECT * FROM `kelas` WHERE `kelas_id` = '$kelasID'";
    $result = $koneksi->query($sql);
    if ($result->num_rows < 1) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Kelas ID tidak terdaftar.'
        )));
    }

    $postNames = array(
        "senin",
        "selasa",
        "rabu",
        "kamis",
        "jumat",
        "sabtu",
        "minggu",
    );
    $sql = "";
    foreach ($postNames as $postname) {
        $hari = ucfirst($postname);
        $masuk = $_POST[$postname]['masuk'];
        $pulang = $_POST[$postname]['pulang'];
        $sql .= "UPDATE `jadwal` SET `jadwal_masuk` = '$masuk', `jadwal_pulang` = '$pulang' WHERE `jadwal_hari` = '$hari' AND `kelas_id` = '$kelasID';";
    }
    if($koneksi->multi_query($sql) === TRUE)
    {
        /// Sukses
        die(json_encode(array(
            'status' => true, 
            'message' => 'Data Jadwal berhasil di Update.'
        )));
    }else{
        /// Terjadi Kesalahan MySQL
        die(json_encode(array(
            'status' => false, 
            'message' => 'Query Gagal : '.$koneksi->error.''
        )));
    }
}