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

    /// Validasi kelasNama
    $kelasNama = $_POST['kelasNama'];
    if (validasi_kelas($kelasNama) == FALSE) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Nama Kelas tidak boleh lebih dari 50 karakter!.'
        )));
    }

    $sql = "SELECT * FROM `kelas` WHERE `kelas_nama` = '$kelasNama'";
    $result = $koneksi->query($sql);
    if ($result->num_rows > 0) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Nama Kelas sudah terdaftar.'
        )));
    }

    $sql = "INSERT INTO `kelas` (`kelas_id`, `kelas_nama`) VALUES (NULL, '$kelasNama');";
    if($koneksi->query($sql) === TRUE)
    {
        $sql = "SELECT * FROM `kelas` WHERE `kelas_nama` = '$kelasNama'";
        $result = $koneksi->query($sql);
        while($row = $result->fetch_assoc()) {
            $kelas_id = $row['kelas_id'];
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
            $sql .= "INSERT INTO `jadwal` (`jadwal_id`, `kelas_id`, `jadwal_hari`, `jadwal_masuk`, `jadwal_pulang`) VALUES (NULL, '$kelas_id', '$hari', '00:00:00', '00:00:00');";
        }
        if($koneksi->multi_query($sql) === TRUE)
        {
            /// Sukses
            die(json_encode(array(
                'status' => true, 
                'message' => 'Data Kelas berhasil di tambahkan.'
            )));
        }else{
            /// Terjadi Kesalahan MySQL
            die(json_encode(array(
                'status' => false, 
                'message' => 'Query Gagal : '.$koneksi->error.''
            )));
        }
    }else{
        /// Terjadi Kesalahan MySQL
        die(json_encode(array(
            'status' => false, 
            'message' => 'Query Gagal : '.$koneksi->error.''
        )));
    }
}