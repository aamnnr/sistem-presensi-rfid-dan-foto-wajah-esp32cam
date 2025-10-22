<?php
function format_hari_tanggal($waktu, $mode = false)
{
    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    $hr = date('w', strtotime($waktu));
    $hari = $hari_array[$hr];
    $tanggal = date('j', strtotime($waktu));
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));

    if($mode == TRUE)
    {
        return "$tanggal $bulan $tahun";
    }else{
        return "$hari, $tanggal $bulan $tahun";
    }
}

function hari_indonesia($hari)
{
    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;

        case 'Mon':
            $hari_ini = "Senin";
            break;

        case 'Tue':
            $hari_ini = "Selasa";
            break;

        case 'Wed':
            $hari_ini = "Rabu";
            break;

        case 'Thu':
            $hari_ini = "Kamis";
            break;

        case 'Fri':
            $hari_ini = "Jumat";
            break;

        case 'Sat':
            $hari_ini = "Sabtu";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }
    return $hari_ini;
}

function jenis_kelamin($jenis_kelamin)
{
    switch ($jenis_kelamin) {
        case 'Laki-laki':
            $jenis_kelamin = "Pria";
            break;

        case 'Perempuan':
            $jenis_kelamin = "Wanita";
            break;

        default:
            $jenis_kelamin = "Unknown";
            break;
    }
    return $jenis_kelamin;
}


/// Jika nik bukan angka
/// Jika panjang nik bukang 16
/// Jika true = valid
/// Jika false = tidak valid
function validasi_absen($absen)
{
    if (!is_numeric($absen)) {
        return false;
    }
    return true;
}

/// Jika nama lebih dari 50
/// Jika true = valid
/// Jika false = tidak valid
function validasi_nama($nama)
{
    if (strlen($nama) < 2 || strlen($nama) > 50) {
        return false;
    }

    return true;
}

/// Jika jenis kelamin bukan M (Male) / F (Female)
/// Jika true = valid
/// Jika false = tidak valid
function validasi_jk($jenis_kelamin)
{
    if ($jenis_kelamin == "Laki-laki" || $jenis_kelamin == "Perempuan") {
        return true;
    }

    return false;
}

/// validasi tanggal dengan function php yaitu checkdate
/// Format parameter Tahun-Bulan-Tanggal
/// Jika true = valid
/// Jika false = tidak valid
function validasi_tanggal($tanggal)
{
    $data = explode('-', $tanggal);
    $tanggal = $data[2];
    $bulan = $data[1];
    $tahun = $data[0];  
    // checkdate ( int $month , int $day , int $year ) : bool
    if (checkdate($bulan, $tanggal, $tahun)) {
        return true;
    }

    return false;
}


/// Jika nik bukan angka
/// Jika panjang nomor hp kurang dari 5 dan lebih dari 20
/// Jika true = valid
/// Jika false = tidak valid
function validasi_nohp($nohp)
{
    if (!is_numeric($nohp)) {
        return false;
    }

    if (strlen($nohp) <= 5 || strlen($nohp) >= 20) {
        return false;
    }

    return true;
}

/// Jika alamat lebih dari 500
/// Jika true = valid
/// Jika false = tidak valid
function validasi_alamat($alamat)
{
    if (strlen($alamat) > 500) {
        return false;
    }

    return true;
}

/// Jika jabatan_nama lebih dari 50
/// Jika true = valid
/// Jika false = tidak valid
function validasi_kelas($kelas_nama)
{
    if (strlen($kelas_nama) > 50) {
        return false;
    }

    return true;
}