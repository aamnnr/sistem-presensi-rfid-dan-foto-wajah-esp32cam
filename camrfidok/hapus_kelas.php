<?php
session_start();
require_once("./config/db.php");
require_once("./config/function.php");

if (!$_SESSION['username']) {
    header('Location: ./auth/login.php');
    exit();
}

if (isset($_GET["kelas_id"])) {
    $id = $_GET["kelas_id"];
    $sql = "SELECT * FROM `kelas` WHERE `kelas_id` = '$id'";
    $result = $koneksi->query($sql);
    if ($result->num_rows < 1) {
        header('location:./data_kelas.php');
        exit();
    }

    $sql = "DELETE FROM `kelas` WHERE `kelas_id` = $id";

    if ($koneksi->query($sql) === TRUE) {
        header('location:./data_kelas.php?msg=1');
        exit();
    } else {
        header('location:./data_kelas.php?msg=2');
        exit();
    }
} else {
    header("location:./data_kelas.php");
    exit();
}