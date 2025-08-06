<?php
session_start();
require_once("./config/db.php"); // Asumsi file ini berisi koneksi $koneksi
require_once("./config/function.php"); // Asumsi file ini berisi fungsi validasi Anda

// Atur header untuk respons JSON
header('Content-Type: application/json');

// --- Validasi Sesi Pengguna ---
// Pastikan pengguna sudah login sebelum mengizinkan penambahan data
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    die(json_encode(array(
        'status' => false,
        'message' => 'Sesi Anda berakhir, Silahkan login ulang.'
    )));
}

// --- Proses Request POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Validasi Kelengkapan Form ---
    // Daftar nama field POST yang wajib diisi
    $requiredPostNames = array(
        "siswaAbsen",
        "siswaNama",
        "siswaKelas",
        "siswaJK",
        "siswaTgl",
        "siswaNohp",
        "siswaAlamat"
    );

    foreach ($requiredPostNames as $postname) {
        // Periksa jika ada field yang kosong atau tidak diatur
        if (!isset($_POST[$postname]) || trim($_POST[$postname]) === '') {
            die(json_encode(array(
                'status' => false,
                'message' => 'Silahkan isi semua form dengan baik dan benar (Field: ' . $postname . ').'
            )));
        }
    }

    // --- Ambil dan Bersihkan Data dari POST ---
    // Gunakan real_escape_string untuk mencegah SQL Injection jika tidak menggunakan prepared statements di semua tempat
    $siswaAbsen = $koneksi->real_escape_string($_POST['siswaAbsen']);
    $siswaNama = $koneksi->real_escape_string($_POST['siswaNama']);
    $siswaKelas = $koneksi->real_escape_string($_POST['siswaKelas']);
    $siswaJenisKelamin = $koneksi->real_escape_string($_POST['siswaJK']);
    $siswaTanggalLahir = $koneksi->real_escape_string($_POST['siswaTgl']);
    $siswaNohp = $koneksi->real_escape_string($_POST['siswaNohp']);
    $siswaAlamat = $koneksi->real_escape_string($_POST['siswaAlamat']);

    // --- Validasi Data Lebih Lanjut Menggunakan Fungsi Anda ---
    // Asumsi fungsi validasi_absen, validasi_nama, validasi_jk, dll. ada di function.php

    // Validasi Absen (contoh: 16 angka)
    if (function_exists('validasi_absen') && validasi_absen($siswaAbsen) == FALSE) {
        die(json_encode(array(
            'status' => false,
            'message' => 'Nomor Absen Tidak Valid! (Contoh: terdiri dari 16 angka).'
        )));
    }

    // Cek duplikasi Absen di database
    // Menggunakan prepared statement untuk cek duplikasi juga lebih aman
    $sql_check_absen = "SELECT `siswa_absen` FROM `siswa` WHERE `siswa_absen` = ?";
    $stmt_check_absen = $koneksi->prepare($sql_check_absen);
    if (!$stmt_check_absen) { // Cek jika prepare gagal
        die(json_encode(['status' => false, 'message' => 'Prepare statement gagal: ' . $koneksi->error]));
    }
    $stmt_check_absen->bind_param("s", $siswaAbsen); // `siswa_absen` di DB adalah VARCHAR(16)
    $stmt_check_absen->execute();
    $result_check_absen = $stmt_check_absen->get_result();
    if ($result_check_absen->num_rows > 0) {
        $stmt_check_absen->close();
        die(json_encode(array(
            'status' => false,
            'message' => 'Nomor Absen sudah terdaftar.'
        )));
    }
    $stmt_check_absen->close();

    // Validasi Nama
    if (function_exists('validasi_nama') && validasi_nama($siswaNama) == FALSE) {
        die(json_encode(array(
            'status' => false,
            'message' => 'Nama minimal 2 karakter dan tidak boleh lebih dari 50 karakter.'
        )));
    }
    
    // Cek duplikasi Nama di database
    // Menggunakan kolom `siswa_nama` sesuai database
    $sql_check_nama = "SELECT `siswa_nama` FROM `siswa` WHERE `siswa_nama` = ?";
    $stmt_check_nama = $koneksi->prepare($sql_check_nama);
    if (!$stmt_check_nama) { // Cek jika prepare gagal
        die(json_encode(['status' => false, 'message' => 'Prepare statement gagal: ' . $koneksi->error]));
    }
    $stmt_check_nama->bind_param("s", $siswaNama);
    $stmt_check_nama->execute();
    $result_check_nama = $stmt_check_nama->get_result();
    if ($result_check_nama->num_rows > 0) {
        $stmt_check_nama->close();
        die(json_encode(array(
            'status' => false,
            'message' => 'Nama siswa sudah terdaftar. Harap gunakan nama lain atau perbarui siswa yang sudah ada.'
        )));
    }
    $stmt_check_nama->close();


    // Validasi Kelas
    $sql_check_kelas = "SELECT * FROM `kelas` WHERE `kelas_id` = ?";
    $stmt_check_kelas = $koneksi->prepare($sql_check_kelas);
    if (!$stmt_check_kelas) { // Cek jika prepare gagal
        die(json_encode(['status' => false, 'message' => 'Prepare statement gagal: ' . $koneksi->error]));
    }
    $stmt_check_kelas->bind_param("i", $siswaKelas); // `kelas_id` di DB adalah INT
    $stmt_check_kelas->execute();
    $result_check_kelas = $stmt_check_kelas->get_result();
    if ($result_check_kelas->num_rows <= 0) {
        $stmt_check_kelas->close();
        die(json_encode(array(
            'status' => false,
            'message' => 'Form Kelas tidak valid.'
        )));
    }
    $stmt_check_kelas->close();

    // Validasi Jenis Kelamin
    if (function_exists('validasi_jk') && validasi_jk($siswaJenisKelamin) == FALSE) {
        die(json_encode(array(
            'status' => false,
            'message' => 'Form Jenis Kelamin tidak valid.'
        )));
    }

    // Validasi Tanggal Lahir
    if (function_exists('validasi_tanggal') && validasi_tanggal($siswaTanggalLahir) == FALSE) {
        die(json_encode(array(
            'status' => false,
            'message' => 'Form Tanggal Lahir tidak valid.'
        )));
    }

    // Validasi No HP
    if (function_exists('validasi_nohp') && validasi_nohp($siswaNohp) == FALSE) {
        die(json_encode(array(
            'status' => false,
            'message' => 'Nomor HP tidak valid.'
        )));
    }

    // Validasi Alamat
    if (function_exists('validasi_alamat') && validasi_alamat($siswaAlamat) == FALSE) {
        die(json_encode(array(
            'status' => false,
            'message' => 'Alamat tidak boleh lebih dari 500 karakter.'
        )));
    }

    // --- INSERT DATA SISWA KE DATABASE ---
    // Nama-nama kolom di query INSERT harus SAMA PERSIS dengan nama kolom di tabel 'siswa' Anda.
    // Berdasarkan DUMP SQL Anda, kolom-kolomnya adalah:
    // `siswa_id`, `kelas_id`, `siswa_nama`, `siswa_absen`, `siswa_jeniskelamin`, `siswa_lahir`, `siswa_nomorhp`, `siswa_alamat`
    // Serta kolom tambahan yang sudah kita diskusikan: `face_embedding`, `siswa_status`

    $sql_insert = "INSERT INTO `siswa`
                   (`siswa_id`, `kelas_id`, `siswa_nama`, `siswa_absen`, `siswa_jeniskelamin`, `siswa_lahir`, `siswa_nomorhp`, `siswa_alamat`, `face_embedding`, `siswa_status`)
                   VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, NULL, ?)"; // Ada 8 tanda tanya, satu untuk siswa_status

    $stmt = $koneksi->prepare($sql_insert);
    if (!$stmt) { // Cek jika prepare gagal
        die(json_encode(['status' => false, 'message' => 'Prepare statement INSERT gagal: ' . $koneksi->error]));
    }

    // Tipe data di DB Anda (dari dump SQL):
    // `kelas_id`: INT(12)
    // `siswa_nama`: VARCHAR(50)
    // `siswa_absen`: VARCHAR(16)
    // `siswa_jeniskelamin`: ENUM('M','F') -> ditangani sebagai string
    // `siswa_lahir`: DATE -> ditangani sebagai string (YYYY-MM-DD)
    // `siswa_nomorhp`: VARCHAR(20)
    // `siswa_alamat`: VARCHAR(500)
    // `siswa_status`: TINYINT(1) (ini yang kita tambahkan dan diset '1') -> ditangani sebagai integer

    // Urutan parameter di bind_param harus sesuai dengan urutan tanda tanya di query
    // Parameters: $siswaKelas, $siswaNama, $siswaAbsen, $siswaJenisKelamin, $siswaTanggalLahir, $siswaNohp, $siswaAlamat, '1' (siswa_status)
    // Tipe:       i           s           s           s                 s                 s          s            i (untuk '1')
    // String tipe: "issssssi" (total 8 karakter, karena ada 8 tanda tanya)

    $siswaStatusDefault = 1; // Menggunakan nilai integer 1 untuk status 'Aktif'
    
    $stmt->bind_param("issssssi",
        $siswaKelas,          // i (kelas_id)
        $siswaNama,           // s (siswa_nama)
        $siswaAbsen,          // s (siswa_absen)
        $siswaJenisKelamin,   // s (siswa_jeniskelamin)
        $siswaTanggalLahir,   // s (siswa_lahir)
        $siswaNohp,           // s (siswa_nomorhp)
        $siswaAlamat,         // s (siswa_alamat)
        $siswaStatusDefault   // i (siswa_status - nilai default 1)
    );

    if ($stmt->execute()) {
        // Sukses
        echo json_encode(array(
            'status' => true,
            'message' => 'Data siswa berhasil disimpan. Sekarang, silakan ambil data wajah untuk ' . $siswaNama . '.'
        ));
    } else {
        // Terjadi Kesalahan MySQL
        echo json_encode(array(
            'status' => false,
            'message' => 'Query Gagal: ' . $stmt->error
        ));
    }

    $stmt->close();
    $koneksi->close();
} else {
    // Jika bukan metode POST
    echo json_encode(array(
        'status' => false,
        'message' => 'Metode request tidak diizinkan.'
    ));
}
?>