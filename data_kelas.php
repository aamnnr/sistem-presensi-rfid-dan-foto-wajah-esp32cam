<?php
$page = "Data Kelas";
require_once("./header.php");
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Data Kelas</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Data Kelas</li>
            </ol>
            <!-- START MESSAGE -->
            <?php
            if (isset($_GET['msg']) && isset($_SERVER['HTTP_REFERER'])) {
                if ($_GET['msg'] == 1 && $_SERVER['HTTP_REFERER']) {
            ?>
            <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                <strong>Berhasil Menghapus Data Kelas!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
                } else if ($_GET['msg'] == 2 && $_SERVER['HTTP_REFERER']) {
            ?>
            <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                <strong>Gagal Menghapus Data Kelas!</strong>
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
                        Data Kelas
                    </div>
                    <div>
                        <a href="./tambah_kelas.php">
                            <button type="submit" class="btn btn-sm btn-primary">
                                Tambah Data Kelas
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Kelas</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $sql = "SELECT * FROM `kelas` ORDER BY `kelas`.`kelas_id` ASC";
                            $result = $koneksi->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $kelas_id = $row['kelas_id'];
                                $kelas_nama = $row['kelas_nama'];
                            ?>
                                <tr>
                                    <td><?php echo $kelas_id; ?></td>
                                    <td><?php echo $kelas_nama; ?></td>
                                    <td>
                                        <a href="edit_kelas.php?kelas_id=<?php echo $kelas_id; ?>"
                                            class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="hapus_kelas.php?kelas_id=<?php echo $kelas_id; ?>"
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