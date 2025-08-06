<?php
$page = "Data Rekap";
require_once("./header.php");
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Data Rekap</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Data Rekap</li>
            </ol>
            <!-- START MESSAGE -->
            <?php
            if (isset($_GET['msg']) && isset($_SERVER['HTTP_REFERER'])) {
                if ($_GET['msg'] == 1 && $_SERVER['HTTP_REFERER']) {
            ?>
            <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                <strong>Berhasil Menghapus Data Rekap!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
                } else if ($_GET['msg'] == 2 && $_SERVER['HTTP_REFERER']) {
            ?>
            <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                <strong>Gagal Menghapus Data Rekap!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
                }
            }
            ?>
            <!-- END MESSAGE -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <i class="fas fa-table mr-1"></i>
                        Data Rekap
                    </div>
                    <form action="./rekap.php" method="get">
                        <div class="row">
                            <div class="col-md-auto">
                                <select class="custom-select" id="kelas_id" name="kelas_id"
                                    autocomplete="off">
                                    <?php
                                        $sql = "SELECT * FROM `kelas` ORDER BY `kelas_id` ASC";
                                        $result = $koneksi->query($sql);

                                        if ($result->num_rows > 0) {
                                            echo '<option value="">- Pilih kelas -</option>';
                                            while ($row = $result->fetch_assoc()) {
                                                $kelasId = $row['kelas_id'];
                                                $kelasNama = $row['kelas_nama'];

                                                echo '<option value="' . $kelasId . '">' . $kelasNama . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">- kelas Tidak Ditemukan -</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-auto">
                                <input type="date" class="form-control" name="tanggal" id="tanggal" autocomplete="off">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Rekap</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Siswa Nama</th>
                                    <th>Siswa Absen</th>
                                    <th>Rekap Tanggal</th>
                                    <th>Siswa Masuk</th>
                                    <th>Siswa Keluar</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $sql = "SELECT `rekap`.`rekap_id`, `rekap`.`rekap_tanggal`, `siswa`.`siswa_nama`, `siswa`.`siswa_absen`, `rekap`.`rekap_masuk`, `rekap`.`rekap_keluar`, `rekap`.`rekap_keterangan` 
                            FROM `rekap`
                            INNER JOIN `siswa` ON `rekap`.`siswa_id` = `siswa`.`siswa_id`";
                            $result = $koneksi->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $rekap_id = $row['rekap_id'];                                
                                $siswa_nama = $row['siswa_nama'];
                                $siswa_absen = $row['siswa_absen'];
                                $rekap_tanggal = $row['rekap_tanggal'];
                                $rekap_masuk = $row['rekap_masuk'];
                                $rekap_keluar = $row['rekap_keluar'];
                                $rekap_keterangan = $row['rekap_keterangan'];
                            ?>
                                <tr>
                                    <td><?php echo $siswa_nama; ?></td>
                                    <td><?php echo $siswa_absen; ?></td>
                                    <td><?php echo $rekap_tanggal; ?></td>
                                    <td><?php echo $rekap_masuk; ?></td>
                                    <td><?php echo $rekap_keterangan; ?></td>
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