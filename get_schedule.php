<?php
// C:\xampp\htdocs\camrfidok\get_schedule.php
header('Content-Type: application/json');

require_once("./config/db.php");

$response = array();

if (!isset($koneksi) || $koneksi->connect_error) {
    $response['status'] = 'error';
    $response['message'] = 'Koneksi database gagal: ' . $koneksi->connect_error;
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama'])) {
    $siswa_nama = $koneksi->real_escape_string($_POST['nama']);
    $current_day_num = date('N'); // N=1 (Senin) to 7 (Minggu)
    $day_map = array(1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu');
    $jadwal_hari_str = $day_map[$current_day_num];

    // Dapatkan kelas_id siswa
    $get_kelas_id_sql = "SELECT kelas_id FROM `siswa` WHERE `siswa_nama` = ?";
    $get_kelas_id_stmt = $koneksi->prepare($get_kelas_id_sql);
    if (!$get_kelas_id_stmt) {
        $response['status'] = 'error';
        $response['message'] = 'Prepare statement get_kelas_id_stmt failed: ' . $koneksi->error;
        echo json_encode($response);
        exit();
    }
    $get_kelas_id_stmt->bind_param("s", $siswa_nama);
    $get_kelas_id_stmt->execute();
    $get_kelas_id_stmt->bind_result($kelas_id);
    $get_kelas_id_stmt->fetch();
    $get_kelas_id_stmt->close();

    if (empty($kelas_id)) {
        $response['status'] = 'not_found';
        $response['message'] = 'Siswa atau kelas tidak ditemukan.';
        echo json_encode($response);
        exit();
    }

    // Ambil jam_masuk, jam_pulang dari tabel jadwal berdasarkan kelas_id dan hari ini
    $sql = "SELECT jadwal_masuk, jadwal_pulang FROM `jadwal` WHERE `kelas_id` = ? AND `jadwal_hari` = ?";
    $stmt = $koneksi->prepare($sql);
    if (!$stmt) {
        $response['status'] = 'error';
        $response['message'] = 'Prepare statement failed: ' . $koneksi->error;
        echo json_encode($response);
        exit();
    }
    $stmt->bind_param("is", $kelas_id, $jadwal_hari_str);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['status'] = 'success';
        $response['jam_masuk'] = $row['jadwal_masuk'];
        $response['jam_pulang'] = $row['jadwal_pulang'];
    } else {
        $response['status'] = 'not_found';
        $response['message'] = 'Jadwal tidak ditemukan untuk siswa dan hari ini.';
    }

    $stmt->close();
    $koneksi->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Request tidak valid.';
}

echo json_encode($response);
?>