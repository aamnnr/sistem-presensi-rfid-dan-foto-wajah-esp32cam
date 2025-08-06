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
    $postNames = array(
        "kelasID",
        "kelasNama",
    );

    foreach ($postNames as $postname) {
        /// JIka ada postname yang panjang lenghtnya 0
        if (!strlen($_POST[$postname])) {
            die(json_encode(array(
                'status' => false, 
                'message' => 'Silahkan isi semua form dengan baik dan benar'
            )));
        }
    }

    /// Validasi kelasID
    $kelasID = $_POST['kelasID'];
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

    /// Validasi kelasNama
    $kelasNama = $_POST['kelasNama'];
    if (validasi_kelas($kelasNama) == FALSE) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Nama Kelas tidak boleh lebih dari 50 karakter!.'
        )));
    }

    $sql = "SELECT * FROM `kelas` WHERE `kelas_nama` = '$kelasNama' AND `kelas_id` <> '$kelasID'";
    $result = $koneksi->query($sql);
    if ($result->num_rows > 0) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Nama Kelas sudah terdaftar.'
        )));
    }

    $sql = "UPDATE `kelas` SET `kelas_nama` = '$kelasNama' WHERE `kelas`.`kelas_id` = '$kelasID';";
    if($koneksi->query($sql) === TRUE)
    {
        /// Sukses
        die(json_encode(array(
            'status' => true, 
            'message' => 'Data Kelas berhasil di Update.'
        )));
    }else{
        /// Terjadi Kesalahan MySQL
        die(json_encode(array(
            'status' => false, 
            'message' => 'Query Gagal : '.$koneksi->error.''
        )));
    }
}