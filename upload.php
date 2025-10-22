<?php
// Set direktori tujuan
$target_dir = "rekapfoto/";

// Buat folder jika belum ada
if (!is_dir($target_dir)) {
    if (mkdir($target_dir, 0777, true)) {
        echo "Folder '$target_dir' berhasil dibuat.<br>";
    } else {
        echo "Error: Gagal membuat folder '$target_dir'.<br>";
        exit;
    }
}

date_default_timezone_set('Asia/Jakarta');

// Generate nama file unik berdasarkan waktu
$datum = time();
$target_file = $target_dir . date('Y.m.d_H-i-s', $datum) . "_" . basename($_FILES["imageFile"]["name"]);

// Debugging informasi file
echo "File Size: " . $_FILES["imageFile"]["size"] . " bytes<br>";
echo "Temp File Path: " . $_FILES["imageFile"]["tmp_name"] . "<br>";
echo "Target File Path: " . $target_file . "<br>";

// Variabel untuk status upload
$uploadOk = 1;

// Periksa apakah file adalah gambar
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["imageFile"]["tmp_name"]);
    if ($check !== false) {
        echo "File adalah gambar - " . $check["mime"] . ".<br>";
    } else {
        echo "Error: File bukan gambar.<br>";
        $uploadOk = 0;
    }
}

// Periksa apakah file sudah ada
if (file_exists($target_file)) {
    echo "Error: File sudah ada.<br>";
    $uploadOk = 0;
}

// Periksa ukuran file
if ($_FILES["imageFile"]["size"] > 500000) {
    echo "Error: Ukuran file terlalu besar (maksimal 500KB).<br>";
    $uploadOk = 0;
}

// Periksa format file yang diizinkan
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
    echo "Error: Hanya file dengan format JPG, JPEG, PNG, dan GIF yang diizinkan.<br>";
    $uploadOk = 0;
}

// Proses upload jika tidak ada error
if ($uploadOk == 1) {
    // Periksa apakah file sementara ada
    if (file_exists($_FILES["imageFile"]["tmp_name"])) {
        // Pindahkan file ke direktori tujuan
        if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
            echo "File berhasil diupload ke: " . $target_file . "<br>";

            // Simpan ke database
            $con = mysqli_connect("localhost", "root", "", "absenrfid");
            if (!$con) {
                die("Error: Gagal terhubung ke database. " . mysqli_connect_error());
            }

            // Update database berdasarkan kondisi tertentu
            $qry1 = "SELECT rekap_id FROM rekap WHERE status1=1";
            $result1 = mysqli_query($con, $qry1);
            if ($result1 && mysqli_num_rows($result1) > 0) {
                $row1 = mysqli_fetch_assoc($result1);
                $sql1 = "UPDATE rekap SET rekap_photomasuk = '$target_file', status1 = 0 WHERE rekap_id = " . $row1['rekap_id'];
                if (mysqli_query($con, $sql1)) {
                    echo "Database berhasil diperbarui untuk rekap_photomasuk.<br>";
                } else {
                    echo "Error: Gagal memperbarui database. " . mysqli_error($con) . "<br>";
                }
            }

            $qry2 = "SELECT rekap_id FROM rekap WHERE status2=1";
            $result2 = mysqli_query($con, $qry2);
            if ($result2 && mysqli_num_rows($result2) > 0) {
                $row2 = mysqli_fetch_assoc($result2);
                $sql2 = "UPDATE rekap SET rekap_photokeluar = '$target_file', status2 = 0 WHERE rekap_id = " . $row2['rekap_id'];
                if (mysqli_query($con, $sql2)) {
                    echo "Database berhasil diperbarui untuk rekap_photokeluar.<br>";
                } else {
                    echo "Error: Gagal memperbarui database. " . mysqli_error($con) . "<br>";
                }
            }

            // Tutup koneksi
            mysqli_close($con);
        } else {
            echo "Error: File gagal dipindahkan ke direktori tujuan.<br>";
        }
    } else {
        echo "Error: File sementara tidak ditemukan.<br>";
    }
} else {
    echo "Error: File tidak diupload karena error di atas.<br>";
}
?>
