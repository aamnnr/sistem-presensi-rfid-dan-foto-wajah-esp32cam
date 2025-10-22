<?php
session_start();
require_once("./config/db.php");
require_once("./config/function.php");

// Pastikan tidak ada output HTML atau error sebelumnya
ob_clean();

// Cek apakah session masih ada
if (!isset($_SESSION['username'])) {
    die(json_encode(array(
        'status' => false,
        'message' => 'Session kamu berakhir, Silahkan login ulang.'
    )));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // VALIDASI FOTO
    $updateSiswaFoto = false;
    $siswaFoto = $_FILES['siswaFoto'];
    if ($siswaFoto['size'] != 0) {
        $updateSiswaFoto = true;
    }

    // Validasi post name yang kosong
    $postNames = array(
        "siswaID", 
        "siswaRfid", 
        "siswaAbsen", 
        "siswaNama", 
        "siswaKelas", 
        "siswaJK", 
        "siswaTgl", 
        "siswaNohp", 
        "siswaAlamat"
    );

    foreach ($postNames as $postname) {
        // Jika ada postname yang panjangnya 0
        if (!strlen($_POST[$postname])) {
            die(json_encode(array(
                'status' => false, 
                'message' => 'Silahkan isi semua form dengan baik dan benar'
            )));
        }
    }

    $siswaID = $_POST['siswaID'];

    // Validasi RFID
    $siswaRFID = $_POST['siswaRfid'];
    if (validasi_rfid($siswaRFID) == FALSE) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'RFID Tidak Valid! RFID terdiri dari 10 angka.'
        )));
    }

    // Cek apakah RFID sudah terdaftar
    $sql = "SELECT `siswa_rfid` FROM `siswa` WHERE `siswa_rfid` = '$siswaRFID' AND `siswa_id` <> '$siswaID'";
    $result = $koneksi->query($sql);
    if ($result->num_rows > 0) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'RFID sudah terdaftar.'
        )));
    }

    // Validasi Absen
    $siswaAbsen = $_POST['siswaAbsen'];
    if (validasi_absen($siswaAbsen) == FALSE) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Absen Tidak Valid! Absen terdiri dari 16 angka.'
        )));
    }

    // Cek apakah Absen sudah terdaftar
    $sql = "SELECT `siswa_absen` FROM `siswa` WHERE `siswa_absen` = '$siswaAbsen' AND `siswa_id` <> '$siswaID'";
    $result = $koneksi->query($sql);
    if ($result->num_rows > 0) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Absen sudah terdaftar.'
        )));
    }

    // Validasi NAMA
    $siswaNama = $_POST['siswaNama'];
    if (validasi_nama($siswaNama) == FALSE) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Nama minimal 2 karakter dan tidak boleh lebih dari 50 karakter.'
        )));
    }

    // Validasi Kelas
    $siswaKelas = $_POST['siswaKelas'];
    $sql = "SELECT * FROM `kelas` WHERE `kelas_id` = '$siswaKelas'";
    $result = $koneksi->query($sql);
    if ($result->num_rows <= 0) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Form Kelas tidak valid.'
        )));
    }

    // Validasi Jenis Kelamin
    $siswaJenisKelamin = $_POST['siswaJK'];
    if (validasi_jk($siswaJenisKelamin) == FALSE) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Form Jenis Kelamin tidak valid.'
        )));
    }

    // Validasi Tanggal Lahir
    $siswaTanggalLahir = $_POST['siswaTgl'];
    if (validasi_tanggal($siswaTanggalLahir) == FALSE) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Form Tanggal Lahir tidak valid.'
        )));
    }

    // Validasi No HP
    $siswaNohp = $_POST['siswaNohp'];
    if (validasi_nohp($siswaNohp) == FALSE) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Nomor HP tidak valid.'
        )));
    }

    // Validasi Alamat
    $siswaAlamat = $_POST['siswaAlamat'];
    if (validasi_alamat($siswaAlamat) == FALSE) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Alamat tidak boleh lebih dari 500 karakter.'
        )));
    }

    // VALIDASI FOTO
    if ($updateSiswaFoto) {
        $targetDir = "./image/";
        $response = validasi_foto($siswaFoto, $targetDir);
        if ($response == FALSE) {
            die(json_encode(array(
                'status' => false, 
                'message' => 'Foto Siswa tidak valid! Maksimal ukuran 10 MB dan ekstensi PNG/JPG/JPEG.'
            )));
        }
        $sql = "UPDATE `siswa` 
                SET `kelas_id` = '$siswaKelas', `siswa_rfid` = '$siswaRFID', `siswa_nama` = '$siswaNama', `siswa_absen` = '$siswaAbsen', `siswa_jeniskelamin` = '$siswaJenisKelamin', `siswa_lahir` = '$siswaTanggalLahir', `siswa_nomorhp` = '$siswaNohp', `siswa_alamat` = '$siswaAlamat', `siswa_foto` = '$response'
                WHERE `siswa`.`siswa_id` = '$siswaID';";
    } else {
        $sql = "UPDATE `siswa` 
                SET `kelas_id` = '$siswaKelas', `siswa_rfid` = '$siswaRFID', `siswa_nama` = '$siswaNama', `siswa_absen` = '$siswaAbsen', `siswa_jeniskelamin` = '$siswaJenisKelamin', `siswa_lahir` = '$siswaTanggalLahir', `siswa_nomorhp` = '$siswaNohp', `siswa_alamat` = '$siswaAlamat'
                WHERE `siswa`.`siswa_id` = '$siswaID';";
    }

    if ($koneksi->query($sql) === TRUE) {
        // Sukses
        die(json_encode(array(
            'status' => true, 
            'message' => 'Data Siswa berhasil di Update.'
        )));
    } else {
        // Terjadi Kesalahan MySQL
        die(json_encode(array(
            'status' => false, 
            'message' => 'Query Gagal: ' . $koneksi->error
        )));
    }
}
?>
