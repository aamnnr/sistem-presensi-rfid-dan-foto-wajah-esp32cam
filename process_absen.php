<?php
// C:\xampp\htdocs\camrfidok\process_absen.php
header('Content-Type: text/plain'); // Atau application/json

require_once("./config/db.php");

if (!isset($koneksi) || $koneksi->connect_error) {
    echo 'gagal: Koneksi database gagal'; 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama'], $_POST['waktu'], $_POST['type'], $_POST['status'])) {
    $siswa_nama = $koneksi->real_escape_string($_POST['nama']); // Gunakan siswa_nama
    $waktu = $koneksi->real_escape_string($_POST['waktu']); // Format: YYYY-MM-DD HH:MM:SS
    $type = $koneksi->real_escape_string($_POST['type']);   // 'check_in' or 'check_out'
    $status = $koneksi->real_escape_string($_POST['status']); // 'on_time', 'late', 'too_late'

    // Periksa apakah siswa sudah melakukan absensi jenis ini hari ini
    $today_date = date('Y-m-d', strtotime($waktu));
    
    $check_sql = "SELECT COUNT(*) FROM `rekap` WHERE `siswa_nama` = ? AND DATE(`rekap_tanggal`) = ? AND `rekap_type` = ?";
    $check_stmt = $koneksi->prepare($check_sql);
    if (!$check_stmt) {
        echo 'gagal: Prepare check_stmt failed: ' . $koneksi->error;
        exit();
    }
    $check_stmt->bind_param("sss", $siswa_nama, $today_date, $type);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        echo 'sudah_absen';
        $koneksi->close();
        exit();
    }

    // Dapatkan siswa_id dari siswa_nama
    $get_siswa_id_sql = "SELECT siswa_id, kelas_id FROM `siswa` WHERE `siswa_nama` = ?"; // Ambil kelas_id juga
    $get_siswa_id_stmt = $koneksi->prepare($get_siswa_id_sql);
    if (!$get_siswa_id_stmt) {
        echo 'gagal: Prepare get_siswa_id_stmt failed: ' . $koneksi->error;
        exit();
    }
    $get_siswa_id_stmt->bind_param("s", $siswa_nama);
    $get_siswa_id_stmt->execute();
    $get_siswa_id_stmt->bind_result($siswa_id, $kelas_id); // Bind kelas_id juga
    $get_siswa_id_stmt->fetch();
    $get_siswa_id_stmt->close();

    if (empty($siswa_id)) {
        echo 'gagal: Siswa tidak ditemukan.';
        $koneksi->close();
        exit();
    }
    
    // Dapatkan jadwal_id berdasarkan kelas_id dan hari
    $current_day_num = date('N', strtotime($waktu)); // N=1 (Senin) to 7 (Minggu)
    $day_map = array(1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu');
    $jadwal_hari_str = $day_map[$current_day_num];

    $get_jadwal_id_sql = "SELECT jadwal_id FROM `jadwal` WHERE `kelas_id` = ? AND `jadwal_hari` = ?";
    $get_jadwal_id_stmt = $koneksi->prepare($get_jadwal_id_sql);
    if (!$get_jadwal_id_stmt) {
        echo 'gagal: Prepare get_jadwal_id_stmt failed: ' . $koneksi->error;
        exit();
    }
    $get_jadwal_id_stmt->bind_param("is", $kelas_id, $jadwal_hari_str);
    $get_jadwal_id_stmt->execute();
    $get_jadwal_id_stmt->bind_result($jadwal_id);
    $get_jadwal_id_stmt->fetch();
    $get_jadwal_id_stmt->close();

    if (empty($jadwal_id)) {
        echo 'gagal: Jadwal tidak ditemukan untuk kelas siswa dan hari ini.';
        $koneksi->close();
        exit();
    }


    // Insert/Update attendance record di tabel `rekap`
    $rekap_tanggal = date('Y-m-d', strtotime($waktu));
    $rekap_time = date('H:i:s', strtotime($waktu)); // Waktu presensi

    // Cek apakah sudah ada record untuk siswa ini di tanggal ini
    $check_rekap_exist_sql = "SELECT rekap_id, rekap_masuk, rekap_keluar FROM `rekap` WHERE `siswa_id` = ? AND `rekap_tanggal` = ?";
    $check_rekap_exist_stmt = $koneksi->prepare($check_rekap_exist_sql);
    $check_rekap_exist_stmt->bind_param("is", $siswa_id, $rekap_tanggal);
    $check_rekap_exist_stmt->execute();
    $rekap_exist_result = $check_rekap_exist_stmt->get_result();
    $existing_rekap = $rekap_exist_result->fetch_assoc();
    $check_rekap_exist_stmt->close();

    if ($existing_rekap) {
        // Record sudah ada, lakukan UPDATE
        if ($type == 'check_in' && empty($existing_rekap['rekap_masuk'])) {
            $sql_action = "UPDATE `rekap` SET `rekap_masuk` = ?, `status1` = ?, `rekap_keterangan` = CONCAT(COALESCE(`rekap_keterangan`, ''), ', Absen Masuk: ', ?) WHERE `rekap_id` = ?";
            $stmt_action = $koneksi->prepare($sql_action);
            $status1_val = ($status == 'on_time') ? 1 : (($status == 'late') ? 2 : 0);
            $keterangan_val = ($status == 'on_time') ? 'Tepat Waktu' : (($status == 'late') ? 'Terlambat' : 'Tidak Valid Masuk');
            $stmt_action->bind_param("sisi", $rekap_time, $status1_val, $keterangan_val, $existing_rekap['rekap_id']);
        } elseif ($type == 'check_out' && empty($existing_rekap['rekap_keluar'])) {
            $sql_action = "UPDATE `rekap` SET `rekap_keluar` = ?, `status2` = ?, `rekap_keterangan` = CONCAT(COALESCE(`rekap_keterangan`, ''), ', Absen Pulang: ', ?) WHERE `rekap_id` = ?";
            $stmt_action = $koneksi->prepare($sql_action);
            $status2_val = 1; // Asumsi check_out selalu 1 (pulang)
            $keterangan_val = 'Tepat Waktu Pulang';
            $stmt_action->bind_param("sisi", $rekap_time, $status2_val, $keterangan_val, $existing_rekap['rekap_id']);
        } else {
            // Sudah absen masuk/keluar, tergantung tipe
            echo 'sudah_absen'; 
            $koneksi->close();
            exit();
        }
    } else {
        // Record belum ada, lakukan INSERT
        if ($type == 'check_in') {
            $sql_action = "INSERT INTO `rekap` (`jadwal_id`, `siswa_id`, `rekap_tanggal`, `rekap_masuk`, `status1`, `rekap_keterangan`) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_action = $koneksi->prepare($sql_action);
            $status1_val = ($status == 'on_time') ? 1 : (($status == 'late') ? 2 : 0);
            $keterangan_val = ($status == 'on_time') ? 'Tepat Waktu' : (($status == 'late') ? 'Terlambat' : 'Tidak Valid Masuk');
            $stmt_action->bind_param("iissis", $jadwal_id, $siswa_id, $rekap_tanggal, $rekap_time, $status1_val, $keterangan_val);
        } else {
            // Tidak bisa absen keluar jika absen masuk belum ada
            echo 'gagal: Absen masuk belum tercatat untuk hari ini.';
            $koneksi->close();
            exit();
        }
    }

    if (!$stmt_action) {
        echo 'gagal: Prepare action_stmt failed: ' . $koneksi->error;
        exit();
    }

    if ($stmt_action->execute()) {
        echo 'berhasil';
    } else {
        echo 'gagal: ' . $stmt_action->error;
    }

    $stmt_action->close();
    $koneksi->close();

} else {
    echo 'gagal: Invalid request parameters.';
}
?>