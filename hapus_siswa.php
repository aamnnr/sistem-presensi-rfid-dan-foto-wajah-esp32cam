<?php
session_start();
require_once("./config/db.php");
require_once("./config/function.php");

if (!$_SESSION['username']) {
    header('Location: ./auth/login.php');
    exit();
}

if (isset($_GET["siswa_id"])) {
    $id = $_GET["siswa_id"];
    $sql = "SELECT * FROM `siswa` WHERE `siswa_id` = '$id'";
    $result = $koneksi->query($sql);
    if ($result->num_rows < 1) {
        header('location:./data_siswa.php');
        exit();
    }

    $sql = "DELETE FROM `siswa` WHERE `siswa_id` = $id";

    if ($koneksi->query($sql) === TRUE) {
        header('location:./data_siswa.php?msg=1');
        exit();
    } else {
        header('location:./data_siswa.php?msg=2');
        exit();
    }
} else {
    header("location:./data_siswa.php");
    exit();
}