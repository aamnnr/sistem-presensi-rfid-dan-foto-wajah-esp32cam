<?php
$page = "Data Siswa";
require_once("./header.php");
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Data Siswa</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Data Siswa</li>
            </ol>
            <!-- START MESSAGE -->
            <?php
            if (isset($_GET['msg']) && isset($_SERVER['HTTP_REFERER'])) {
                if ($_GET['msg'] == 1 && $_SERVER['HTTP_REFERER']) {
            ?>
            <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                <strong>Berhasil Menghapus Data Siswa!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
                } else if ($_GET['msg'] == 2 && $_SERVER['HTTP_REFERER']) {
            ?>
            <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                <strong>Gagal Menghapus Data Siswa!</strong>
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
                        Data Siswa
                    </div>
                    <div>
                        <a href="./tambah_siswa.php">
                            <button type="submit" class="btn btn-sm btn-primary">
                                Tambah Data SIswa
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Absen</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Kelas</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $sql = "SELECT `siswa`.`siswa_id`, `siswa`.`siswa_nama`, `siswa`.`siswa_absen`, `siswa`.`siswa_jeniskelamin`, `siswa`.`siswa_lahir`, `kelas`.`kelas_nama` 
                                    FROM `siswa`
                                    INNER JOIN `kelas` ON `siswa`.`kelas_id` = `kelas`.`kelas_id`";
                            $result = $koneksi->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $siswa_id = $row['siswa_id'];
                                $siswa_nama = $row['siswa_nama'];
                                $siswa_absen = $row['siswa_absen'];
                                $siswa_jeniskelamin = $row['siswa_jeniskelamin'];
                                $siswa_lahir = $row['siswa_lahir'];
                                $kelas_nama = $row['kelas_nama'];
                            ?>
                                <tr>
                                    <td><?php echo $siswa_nama; ?></td>
                                    <td><?php echo $siswa_absen; ?></td>
                                    <td><?php echo jenis_kelamin($siswa_jeniskelamin); ?></td>
                                    <td><?php echo format_hari_tanggal($siswa_lahir, true); ?></td>
                                    <td><?php echo $kelas_nama; ?></td>
                                    <td>
                                        <a href="edit_siswa.php?siswa_id=<?php echo $siswa_id; ?>"
                                            class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="hapus_siswa.php?siswa_id=<?php echo $siswa_id; ?>"
                                            class="btn btn-danger" onclick="return confirm('Apakah anda yakin?')"><i
                                                class="fas fa-trash"></i> Hapus</a>
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