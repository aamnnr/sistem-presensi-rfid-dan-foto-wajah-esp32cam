<?php
error_reporting(E_ALL ^ E_DEPRECATED);

$koneksi = mysqli_connect("localhost", "root", "", "absenrfid");

// Periksa koneksi database
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Jakarta');

// Validasi parameter GET
if (!isset($_GET['Data']) || empty($_GET['Data'])) {
    die("Error: Parameter 'Data' tidak ditemukan atau kosong.");
}

// Sanitasi input
$data = mysqli_real_escape_string($koneksi, $_GET['Data']);

// Query untuk mencari data siswa
$qry = "SELECT * FROM siswa WHERE siswa_rfid='$data'";
$qry_ksr = mysqli_query($koneksi, $qry);

// Periksa apakah ada hasil
if ($qry_ksr && mysqli_num_rows($qry_ksr) > 0) {
    $row_ksr = mysqli_fetch_array($qry_ksr);
    $row_cnt = mysqli_num_rows($qry_ksr);

    if ($row_cnt) {
        // Ambil kelas_id siswa
        $qry_kelas = "SELECT kelas_id FROM siswa WHERE siswa_rfid='$data'";
        $result_kelas = mysqli_query($koneksi, $qry_kelas);
        
        if ($result_kelas && mysqli_num_rows($result_kelas) > 0) {
            $row_kelas = mysqli_fetch_assoc($result_kelas);
            $kelas_id = $row_kelas['kelas_id'];
            
            // Cari jadwal berdasarkan kelas dan hari saat ini
            $hari_ini = date('l'); // Nama hari dalam bahasa Inggris
            $hari_map = [
                'Monday'    => 'Senin',
                'Tuesday'   => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday'  => 'Kamis',
                'Friday'    => 'Jumat',
                'Saturday'  => 'Sabtu',
                'Sunday'    => 'Minggu'
            ];
            $hari_indonesia = $hari_map[$hari_ini];
            
            $qry_jadwal = "SELECT jadwal_id 
                           FROM jadwal 
                           WHERE kelas_id = $kelas_id 
                           AND jadwal_hari = '$hari_indonesia' 
                           AND CURTIME() BETWEEN jadwal_masuk AND jadwal_pulang 
                           LIMIT 1";
            $result_jadwal = mysqli_query($koneksi, $qry_jadwal);
            
            if ($result_jadwal && mysqli_num_rows($result_jadwal) > 0) {
                $row_jadwal = mysqli_fetch_assoc($result_jadwal);
                $jadwal_id = $row_jadwal['jadwal_id'];
            } else {
                die("Tidak jadwal untuk kelas anda hari ini.");
            }
        } else {
            die("Kelas siswa tidak ditemukan.");
        }

        // Cek apakah siswa sudah hadir sebelumnya pada hari ini
        $SQL1 = "SELECT * FROM rekap WHERE siswa_id=" . $row_ksr['siswa_id'] . " AND rekap_tanggal=CURDATE() ORDER BY rekap_tanggal DESC LIMIT 1";
        $qry_ksr1 = mysqli_query($koneksi, $SQL1);
        $row_ksr1 = mysqli_fetch_array($qry_ksr1);
        $row_cnt1 = mysqli_num_rows($qry_ksr1);

        if ($row_cnt1) {
            $SQL2 = "UPDATE rekap SET rekap_keluar = CURTIME(), status2=1 WHERE rekap_id =" . $row_ksr1['rekap_id'];
            mysqli_query($koneksi, $SQL2);
            echo "Selamat Jalan|" . $row_ksr['siswa_nama']; // Mengirim pesan dan nama siswa
        } else {
            $qry = "INSERT INTO rekap (jadwal_id, siswa_id, rekap_tanggal, rekap_masuk, status1) VALUES ($jadwal_id, " . $row_ksr['siswa_id'] . ", CURDATE(), CURTIME(), '1')";
            mysqli_query($koneksi, $qry);
            echo "Selamat Datang|" . $row_ksr['siswa_nama']; // Mengirim pesan dan nama siswa
        }
        
    }
} else {
    $sql_check = "SELECT * FROM rfid_code WHERE rfid_code = '$data'";
$result_check = mysqli_query($koneksi, $sql_check);

if (mysqli_num_rows($result_check) == 0) {
    // Jika data tidak ditemukan, lakukan INSERT
    $sql_insert = "INSERT INTO rfid_code (rfid_code) VALUES ('$data')";
    mysqli_query($koneksi, $sql_insert);
}
}
?>