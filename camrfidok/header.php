<?php
session_start();
require_once("./config/db.php");
require_once("./config/function.php");
if (!$_SESSION['username']) {
    header('Location: ./auth/login.php');
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $page; ?> - Absensi</title>
    <link href="./assets/css/bootstrap.css" rel="stylesheet">
    <link href="./assets/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="./assets/js/all.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="./"><img src="assets/images/logo.png" alt="logo" style="width:50px;height:50px;"></a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i
                class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link <?php echo ($page == 'Dashboard') ? "active" : "" ?>" href="./">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link <?php echo ($page == 'Data Siswa') ? "active" : "" ?>"
                            href="./data_siswa.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Data Siswa
                        </a>
                        <a class="nav-link <?php echo ($page == 'Data Kelas') ? "active" : "" ?>"
                            href="./data_kelas.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-id-card"></i></div>
                            Data Kelas
                        </a>
                        <a class="nav-link <?php echo ($page == 'Data Rekap') ? "active" : "" ?>"
                            href="./data_rekap.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                            Data Rekap
                        </a>
                        <a class="nav-link <?php echo ($page == 'Data Jadwal') ? "active" : "" ?>"
                            href="./data_jadwal.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                            Data Jadwal
                        </a>
                        <a class="nav-link" href="./auth/logout.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Log out
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Masuk sebagai:</div>
                    <?php echo $username; ?>
                </div>
            </nav>
        </div>