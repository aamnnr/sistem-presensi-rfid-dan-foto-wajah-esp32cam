<?php
$page = "Data Siswa";
require_once("./header.php");
// Asumsi $koneksi sudah didefinisikan di header.php untuk koneksi database
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Tambah Data Siswa</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="./data_siswa.php">Data Siswa</a></li>
                <li class="breadcrumb-item active">Tambah Data Siswa</li>
            </ol>
            <div id="response"></div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-plus-square mr-1"></i>
                    Tambah Data Siswa
                </div>
                <div class="card-body">
                    <form class="mb-5" action="./tambah_siswa_post.php" method="POST" id="appsform">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaAbsen">Nomor Absen</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="siswaAbsen" name="siswaAbsen"
                                    placeholder="Masukkan Nomor Absen" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaNama">Nama Lengkap</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="siswaNama" name="siswaNama"
                                    placeholder="Masukkan Nama Lengkap" autocomplete="off" minlength="2" maxlength="50"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaKelas">Kelas</label>
                            <div class="col-sm-8">
                                <select class="custom-select" id="siswaKelas" name="siswaKelas"
                                    autocomplete="off" required>
                                        <?php
                                            $sql = "SELECT * FROM `kelas` ORDER BY `kelas_id` ASC";
                                            $result = $koneksi->query($sql);

                                            if ($result->num_rows > 0) {
                                                echo '<option value="">- Pilih Kelas -</option>';
                                                while ($row = $result->fetch_assoc()) {
                                                    $kelasId = $row['kelas_id'];
                                                    $kelasNama = $row['kelas_nama'];
                                                    echo '<option value="' . $kelasId . '">' . $kelasNama . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">- Kelas Tidak Ditemukan -</option>';
                                            }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaJK">Jenis Kelamin</label>
                            <div class="col-sm-8">
                                <select class="custom-select" id="siswaJK" name="siswaJK" autocomplete="off"
                                    required>
                                    <option value="">- Pilih Jenis Kelamin -</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaTgl">Tanggal Lahir</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="siswaTgl" name="siswaTgl"
                                    autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaNohp">Nomor Telepon <small
                                        style="color:red">Contoh : 62822xxxx4496</small></label>
                            <div class="col-sm-8">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">+</div>
                                    </div>
                                    <input type="number" class="form-control" id="siswaNohp" name="siswaNohp"
                                        placeholder="Masukkan Nomor Telepon" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaAlamat">Alamat Lengkap</label>
                            <div class="col-sm-8">
                                <textarea type="text" class="form-control" id="siswaAlamat" name="siswaAlamat"
                                    placeholder="Masukkan Alamat Lengkap" minlength="4" maxlength="500"
                                    autocomplete="off" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                            </div>
                        </div>
                    </form>

                    <div id="faceCaptureSection" style="display:none; text-align: center; margin-top: 30px;">
                        <h3>2. Ambil Data Wajah</h3>
                        <p id="captureInstructions">Pastikan wajah Anda berada di area kamera. Klik 'Mulai Kamera' untuk memulai.</p>
                        <video id="webcamVideo" width="320" height="240" autoplay style="border: 1px solid #ccc;"></video>
                        <br>
                        <button id="startWebcam" class="btn btn-info mt-2">Mulai Kamera</button>
                        <canvas id="faceCanvas" width="320" height="240" style="display:none;"></canvas>
                        <p id="captureStatus" class="mt-2"></p>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <?php
    require_once("./footer.php");
    ?>
    <script>
    var studentNameForFaceCapture = ""; // Variabel untuk menyimpan nama siswa
    var webcamStream; // Variabel untuk menyimpan stream kamera
    var videoElement = document.getElementById('webcamVideo');
    var canvasElement = document.getElementById('faceCanvas');
    var captureContext = canvasElement.getContext('2d');
    var captureCount = 0;
    var totalCapturesNeeded = 50; // Jumlah sampel wajah yang diinginkan
    var capturedImagesBase64 = []; // Array untuk menyimpan gambar Base64 yang akan dikirim

    $(document).ready(function() {
        // --- Event Listener Submit Form Data Tekstual ---
        $("form#appsform").submit(function(event) {
            event.preventDefault(); // Mencegah submit form bawaan

            var postdata = new FormData(this);
            var postaction = $(this).attr('action'); // Akan mengambil './tambah_siswa_post.php'
            studentNameForFaceCapture = $('#siswaNama').val(); // Ambil nama siswa

            $.ajax({
                type: "POST",
                url: postaction,
                timeout: false,
                data: postdata,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(response) {
                    var responseDiv = $("#response");
                    if (response.status) { // Jika status adalah TRUE (berhasil)
                        responseDiv.html(
                            '<div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            response.message + // Pesan dari server
                            '</div>'
                        );
                        // KOREKSI DI SINI: Gunakan trigger('reset')
                        $("#appsform").trigger('reset'); // Reset form tekstual
                        $("#appsform").hide();           // Sembunyikan form tekstual
                        $("#faceCaptureSection").show(); // Tampilkan bagian pengambilan wajah
                        $("#captureInstructions").text(`Siap untuk mengambil data wajah untuk: ${studentNameForFaceCapture}`);
                    } else { // Jika status adalah FALSE (gagal)
                        responseDiv.html(
                            '<div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            response.message + '</div>'
                        );
                    }
                },
                error: function(jqXHR, textStatus, errorMessage) {
                    $("#response").html(
                        '<div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        'Error AJAX: ' + errorMessage + '</div>');
                },
                beforeSend: function() {
                    $("#response").html(
                        '<div class="alert alert-warning alert-dismissible fade show text-center h4" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Loading...</div>'
                    );
                }
            });
            return false;
        });

        // --- Logika Pengambilan Wajah via WebRTC ---

        // Fungsi untuk memulai webcam
        $('#startWebcam').click(function() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function(stream) {
                        webcamStream = stream;
                        videoElement.srcObject = stream;
                        videoElement.play();
                        $('#startWebcam').hide(); // Sembunyikan tombol 'Mulai Kamera'
                        $('#captureInstructions').text(`Arahkan wajah Anda ke kamera. Mengambil ${totalCapturesNeeded} sampel.`);
                        
                        // Mulai proses pengambilan otomatis
                        captureCount = 0; // Reset counter
                        capturedImagesBase64 = []; // Reset array
                        setTimeout(captureAndSendFace, 500); // Mulai setelah sedikit delay
                    })
                    .catch(function(err) {
                        $('#captureStatus').text('Gagal mengakses kamera: ' + err.name + '. Pastikan kamera terhubung dan diizinkan.');
                        console.error('Error accessing webcam: ', err);
                    });
            } else {
                $('#captureStatus').text('Browser Anda tidak mendukung WebRTC untuk akses kamera. Gunakan browser modern (Chrome/Firefox/Edge).');
            }
        });

        // Fungsi untuk mengambil gambar satu per satu secara otomatis
        function captureAndSendFace() {
            if (captureCount >= totalCapturesNeeded) {
                // Semua sampel sudah diambil
                stopWebcam();
                $('#captureStatus').text('Pengambilan wajah selesai. Mengirimkan data untuk diproses...');
                sendAllCapturedFaces(); // Kirim semua gambar yang terkumpul
                return;
            }

            // Gambar dari video ke canvas
            captureContext.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
            // Dapatkan data gambar sebagai Base64
            var imageData = canvasElement.toDataURL('image/jpeg', 0.9); // Kualitas 90%

            // Tambahkan ke array yang akan dikirim
            capturedImagesBase64.push(imageData);
            captureCount++;
            $('#captureStatus').text(`Mengambil sampel: ${captureCount}/${totalCapturesNeeded}`);

            // Set timeout untuk pengambilan berikutnya
            setTimeout(captureAndSendFace, 200); // Ambil setiap 200ms
        }
        
        // Fungsi untuk menghentikan webcam
        function stopWebcam() {
            if (webcamStream) {
                webcamStream.getTracks().forEach(track => track.stop());
                videoElement.srcObject = null;
            }
        }

        // Fungsi untuk mengirim semua gambar yang terkumpul ke PHP backend
        function sendAllCapturedFaces() {
            $('#captureStatus').text('Memproses dan mengirimkan wajah... Ini mungkin memakan waktu beberapa detik.');
            
            $.ajax({
                type: "POST",
                url: "./app_python/process_face_capture.php", // Endpoint PHP baru
                data: {
                    nama: studentNameForFaceCapture,
                    images: capturedImagesBase64 // Mengirim array gambar Base64
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status) {
                        $("#response").html(
                            '<div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            response.message + '</div>'
                        );
                        $('#faceCaptureSection').hide(); // Sembunyikan bagian pengambilan wajah
                        $("#appsform").show(); // Tampilkan kembali form tekstual untuk pendaftaran baru
                        $('#startWebcam').show(); // Reset tombol 'Mulai Kamera'
                        
                        // Reset variabel untuk pendaftaran berikutnya
                        captureCount = 0; 
                        capturedImagesBase64 = [];
                        studentNameForFaceCapture = "";
                        $('#captureStatus').text(''); // Kosongkan status
                    } else {
                        $("#response").html(
                            '<div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            response.message + '</div>'
                        );
                        // Jika gagal, berikan opsi untuk mencoba lagi
                        $('#startWebcam').show(); // Tampilkan tombol 'Mulai Kamera' agar bisa mencoba lagi
                        $('#captureStatus').text('Gagal memproses. Coba lagi atau periksa log server.');
                    }
                },
                error: function(jqXHR, textStatus, errorMessage) {
                    $("#response").html(
                        '<div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        'Error AJAX saat mengirim wajah: ' + errorMessage + '</div>');
                    $('#startWebcam').show();
                    $('#captureStatus').text('Kesalahan jaringan. Coba lagi.');
                },
                beforeSend: function() {
                    $('#captureStatus').text('Memproses dan mengirimkan wajah... Mohon tunggu.');
                }
            });
        }
    });
    </script>