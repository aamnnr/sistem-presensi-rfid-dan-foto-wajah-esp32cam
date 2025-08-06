<?php
require_once("./config/db.php");
require_once("./config/function.php");

if (isset($_GET['tanggal']) && isset($_GET['kelas_id'])) {
    // Pastikan kelas_id ada
    if ($_GET['kelas_id'] == NULL) {
        $mode = 0;
        $kelas_id = $_GET['kelas_id'];
    } else {
        $mode = 1;
        $kelas_id = $_GET['kelas_id'];
    }
} else {
    header("location:./");
    exit();
}

// Pengecekan dan pemisahan tanggal
if (isset($_GET['tanggal']) && strlen($_GET['tanggal']) === 10) {
    $data_tanggal = explode('-', $_GET['tanggal']);
    
    // Memastikan tanggal terpisah menjadi 3 bagian (tahun, bulan, tanggal)
    if (count($data_tanggal) == 3) {
        $tahun = intval($data_tanggal[0]);
        $bulan = intval($data_tanggal[1]);
        $tanggal = intval($data_tanggal[2]);
    } else {
        die('Format tanggal tidak valid');
    }
} else {
    die('Tanggal tidak ditemukan');
}

// Menghitung jumlah hari pada bulan dan tahun yang diberikan
$jumlah_tanggal_pada_bulan = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rekap</title>
    <link href="./assets/css/bootstrap.css" rel="stylesheet">
    <link href="./assets/css/rekap.css" rel="stylesheet">
</head>
<div class="container-fluid">
    <h1 class="text-center">REKAP BULAN KE-<?php echo $bulan; ?></h1>
    <table id="employee_data" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th class="nama">Nama Siswa</th>
                <?php
                for ($i = 1; $i <= $jumlah_tanggal_pada_bulan; $i++) {
                    echo "<th scope='row'>$i</th>";
                }
                ?>
                <th>Jumlah</th>
            </tr>
        </thead>
        <button type="button" id="export_button" class="btn btn-success btn-sm">Export</button>
        <?php
        $no = 1;
        if ($mode == 1) {
            $sql = "SELECT * FROM `siswa` WHERE `kelas_id` = '$kelas_id' ORDER BY `siswa_id` ASC";
        } else {
            $sql = "SELECT * FROM `siswa` ORDER BY `siswa_id` ASC";
        }
        $query = $koneksi->query($sql);
        while ($data = $query->fetch_array()) {
            $data_result[] = array(
                'siswa_id' => $data['siswa_id'],
                'siswa_nama' => $data['siswa_nama'],
                'kelas_id' => $data['kelas_id'],
            );
        }
        ?>

        <?php
        foreach ($data_result as $row) {
            $siswa_id = $row['siswa_id'];
            $kelas_id = $row['kelas_id'];
        ?>
            <tr>
                <td style="text-align: center;"><?php echo $no ?></td>
                <td><?php echo $row['siswa_nama'] ?></td>

                <?php
                $data_absen = $koneksi->query("SELECT `rekap_tanggal` FROM `rekap` WHERE MONTH(`rekap_tanggal`)='$bulan' GROUP BY `rekap_tanggal`");
                $sql = "SELECT *, COUNT(siswa_id) jumlah FROM `rekap` WHERE MONTH(`rekap_tanggal`) ='$bulan' AND siswa_id='$row[siswa_id]'";
                $jumlah = $koneksi->query($sql);
                $jumlah_kehadiran = $jumlah->fetch_array();

                for ($i = 1; $i <= $jumlah_tanggal_pada_bulan; $i++) {
                    $full_tanggal = "$tahun-$bulan-$i";
                    $sql = "SELECT * FROM `rekap` WHERE `rekap_tanggal` = '$full_tanggal' AND `siswa_id` = '$row[siswa_id]' GROUP BY `siswa_id`";
                    $kehadiran = $koneksi->query($sql);
                    $data_kehadiran = $kehadiran->fetch_array();

                    if ($data_kehadiran && intval(substr($data_kehadiran['rekap_tanggal'], 8)) == $i) {
                        $keterangan = $data_kehadiran['rekap_keterangan'];

                        // Menampilkan status kehadiran tanpa izin atau libur
                        if ($keterangan == 'Hadir Masuk') {
                            echo "<td class='orange'>H.M</td>";
                        } else if ($keterangan == 'Hadir Pulang') {
                            echo "<td class='green'>H.P</td>";
                        } else if ($keterangan == 'Hadir Terlambat') {
                            echo "<td class='red'>H.T</td>";
                        }
                    } else {
                        echo "<td class='null'>-</td>";
                    }
                }
                echo "<td class='null'>" . $jumlah_kehadiran['jumlah'] . "</td>";
                echo "</tr>";
                $no++;
            }
        ?>
    </table>
</div>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
    function html_table_to_excel(type) {
        var data = document.getElementById('employee_data');
        var file = XLSX.utils.table_to_book(data, { sheet: "sheet1" });
        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
        XLSX.writeFile(file, 'file.' + type);
    }

    const export_button = document.getElementById('export_button');

    export_button.addEventListener('click', () => {
        html_table_to_excel('xlsx');
    });
</script>
