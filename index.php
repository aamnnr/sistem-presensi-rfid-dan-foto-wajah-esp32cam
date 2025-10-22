<?php
$page = "Dashboard";
require_once("./header.php");
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Rekap Presensi <small>Hari Ini</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Masuk</th>
                                    <th>Pulang</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $sql = "SELECT `rekap`.`rekap_id`, `rekap`.`rekap_tanggal`, `kelas`.`kelas_nama`, `siswa`.`kelas_id`, `siswa`.`siswa_nama`, `rekap`.`rekap_masuk`, `rekap`.`rekap_keluar`, `rekap`.`rekap_keterangan` 
                            FROM `rekap`
                            INNER JOIN `siswa` ON `rekap`.`siswa_id` = `siswa`.`siswa_id`
                            INNER JOIN `kelas` ON `siswa`.`kelas_id` = `kelas`.`kelas_id`
                            WHERE `rekap_tanggal` = CURRENT_DATE";
                            $result = $koneksi->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $rekap_id = $row['rekap_id'];
                                $tanggal = $row['rekap_tanggal'];
                                $nama = $row['siswa_nama'];
                                $kelas = $row['kelas_nama'];
                                $masuk = $row['rekap_masuk'];
                                $pulang = $row['rekap_keluar'];
                                $status = $row['rekap_keterangan'];
                            ?>
                                <tr>
                                    <td><?php echo $tanggal ?></td>
                                    <td><?php echo $nama ?></td>
                                    <td><?php echo $kelas ?></td>
                                    <td><?php echo $masuk ?></td>
                                    <td><?php echo $pulang ?></td>
                                    <td><?php echo $status ?></td>
                                    <td>
                                        <a href="edit_rekap.php?rekap_id=<?php echo $rekap_id; ?>"
                                            class="btn btn-primary"><i class="fas fa-edit"> </i> Lihat atau Edit</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
require_once("./footer.php");
?>