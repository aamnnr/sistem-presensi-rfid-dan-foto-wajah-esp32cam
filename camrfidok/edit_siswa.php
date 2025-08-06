<?php
$page = "Data Siswa";
require_once("./header.php");
// Asumsi $koneksi sudah didefinisikan di header.php untuk koneksi database

// Dapatkan siswa_id dari URL - KOREKSI DI SINI
$siswa_id = $_GET['siswa_id'] ?? null; // Menggunakan 'siswa_id' seperti yang Anda gunakan di URL sebelumnya

if (empty($siswa_id)) {
    // Jika siswa_id tidak ada atau kosong, redirect kembali ke data_siswa.php
    header('location:./data_siswa.php');
    exit();
}

// Ambil data siswa dari database menggunakan Prepared Statement
$sql_get_siswa = "SELECT s.*, k.kelas_nama 
                  FROM `siswa` s 
                  JOIN `kelas` k ON s.kelas_id = k.kelas_id 
                  WHERE s.siswa_id = ?";
$stmt_get_siswa = $koneksi->prepare($sql_get_siswa);

// Cek jika prepare gagal
if (!$stmt_get_siswa) {
    die("Error preparing statement: " . $koneksi->error);
}

$stmt_get_siswa->bind_param("i", $siswa_id); // 'i' karena siswa_id adalah integer
$stmt_get_siswa->execute();
$result_get_siswa = $stmt_get_siswa->get_result();

if ($result_get_siswa->num_rows === 0) {
    // Jika siswa tidak ditemukan dengan ID tersebut
    header('location:./data_siswa.php');
    exit();
}

$siswa_data = $result_get_siswa->fetch_assoc();
$stmt_get_siswa->close();

?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Edit Data Siswa</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="./data_siswa.php">Data Siswa</a></li>
                <li class="breadcrumb-item active">Edit Data Siswa</li>
            </ol>
            <div id="response"></div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit mr-1"></i>
                    Edit Data Siswa: <?php echo htmlspecialchars($siswa_data['siswa_nama']); ?>
                </div>
                <div class="card-body">
                    <form class="mb-5" action="./edit_siswa_post.php" method="POST" id="editAppsform">
                        <input type="hidden" name="siswaId" value="<?php echo htmlspecialchars($siswa_data['siswa_id']); ?>">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaAbsen">Nomor Absen</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="siswaAbsen" name="siswaAbsen"
                                    placeholder="Masukkan Nomor Absen" autocomplete="off" required
                                    value="<?php echo htmlspecialchars($siswa_data['siswa_absen']); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaNama">Nama Lengkap</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="siswaNama" name="siswaNama"
                                    placeholder="Masukkan Nama Lengkap" autocomplete="off" minlength="2" maxlength="50"
                                    required value="<?php echo htmlspecialchars($siswa_data['siswa_nama']); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaKelas">Kelas</label>
                            <div class="col-sm-8">
                                <select class="custom-select" id="siswaKelas" name="siswaKelas"
                                    autocomplete="off" required>
                                        <?php
                                            $sql_kelas = "SELECT * FROM `kelas` ORDER BY `kelas_id` ASC";
                                            $result_kelas = $koneksi->query($sql_kelas);
                                            if ($result_kelas->num_rows > 0) {
                                                echo '<option value="">- Pilih Kelas -</option>';
                                                while ($row_kelas = $result_kelas->fetch_assoc()) {
                                                    $selected = ($row_kelas['kelas_id'] == $siswa_data['kelas_id']) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($row_kelas['kelas_id']) . '" ' . $selected . '>' . htmlspecialchars($row_kelas['kelas_nama']) . '</option>';
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
                                    <option value="Laki-laki" <?php echo ($siswa_data['siswa_jeniskelamin'] == 'Laki-laki' || $siswa_data['siswa_jeniskelamin'] == 'M') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php echo ($siswa_data['siswa_jeniskelamin'] == 'Perempuan' || $siswa_data['siswa_jeniskelamin'] == 'F') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaTgl">Tanggal Lahir</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="siswaTgl" name="siswaTgl"
                                    autocomplete="off" required value="<?php echo htmlspecialchars($siswa_data['siswa_lahir']); ?>">
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
                                        placeholder="Masukkan Nomor Telepon" autocomplete="off" required
                                        value="<?php echo htmlspecialchars($siswa_data['siswa_nomorhp']); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="siswaAlamat">Alamat Lengkap</label>
                            <div class="col-sm-8">
                                <textarea type="text" class="form-control" id="siswaAlamat" name="siswaAlamat"
                                    placeholder="Masukkan Alamat Lengkap" minlength="4" maxlength="500"
                                    autocomplete="off" required><?php echo htmlspecialchars($siswa_data['siswa_alamat']); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <button type="button" id="updateFaceDataBtn" class="btn btn-warning">Perbarui Data Wajah</button>
                                <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                            </div>
                        </div>
                    </form>

                    <div id="faceCaptureSection" style="display:none; text-align: center; margin-top: 30px;">
                        <h3>Perbarui Data Wajah Siswa: <?php echo htmlspecialchars($siswa_data['siswa_nama']); ?></h3>
                        <p id="captureInstructions">Pastikan wajah Anda berada di area kamera. Klik 'Mulai Kamera' untuk memulai.</p>
                        <div style="position: relative; width: 320px; height: 240px; margin: auto;">
                            <video id="webcamVideo" width="320" height="240" autoplay style="position: absolute; top: 0; left: 0;"></video>
                            <canvas id="faceCanvas" width="320" height="240" style="position: absolute; top: 0; left: 0;"></canvas>
                        </div>
                        <br>
                        <button id="startWebcam" class="btn btn-info mt-2">Mulai Kamera</button>
                        <button id="stopWebcamButton" class="btn btn-danger mt-2" style="display:none;">Stop Kamera</button>
                        <p id="captureStatus" class="mt-2"></p>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <?php
    require_once("./footer.php");
    ?>

    <script async src="./assets/js/opencv.js" type="text/javascript"></script>

    <script>
    // Pastikan ini adalah siswa_nama, karena itu yang digunakan di process_face_capture.php
    var studentNameForFaceCapture = "<?php echo htmlspecialchars($siswa_data['siswa_nama']); ?>";
    var studentIdForFaceCapture = "<?php echo htmlspecialchars($siswa_data['siswa_id']); ?>"; 

    var webcamStream;
    var videoElement = document.getElementById('webcamVideo');
    var canvasElement = document.getElementById('faceCanvas');
    var captureContext = canvasElement.getContext('2d');
    var captureCount = 0;
    var totalCapturesNeeded = 50;
    var capturedImagesBase64 = [];
    var isCapturing = false;
    var faceCascade; // Variabel untuk model Haar Cascade
    var utils; // Utilities dari OpenCV.js

    // --- Inisialisasi OpenCV.js ---
    function onOpenCvReady() {
        if (typeof cv !== 'undefined') {
            utils = new Utils('errorMessage');
            var faceCascadeUrl = './app_python/data/haarcascade_frontalface_default.xml'; 
            utils.createFileFromUrl('haarcascade_frontalface_default.xml', faceCascadeUrl, () => {
                faceCascade = new cv.CascadeClassifier();
                faceCascade.load('haarcascade_frontalface_default.xml');
                console.log('Haar Cascade model loaded.');
                $('#captureInstructions').text('Model deteksi wajah dimuat. Klik \'Mulai Kamera\'.');
            }, (err) => {
                console.error("Failed to load Haar Cascade XML:", err);
                $('#captureStatus').text('Error: Gagal memuat model deteksi wajah. Pastikan file ada.');
            });
        } else {
            console.error('OpenCV.js is not loaded.');
            $('#captureStatus').text('Error: OpenCV.js tidak dimuat. Coba periksa koneksi internet atau path file.');
        }
    }

    if (typeof cv !== 'undefined' && cv.onRuntimeInitialized) {
        cv.onRuntimeInitialized = onOpenCvReady;
    } else {
        // Fallback untuk browser yang lebih tua atau jika cv.onRuntimeInitialized belum disetel
        window.onload = onOpenCvReady; 
    }

    $(document).ready(function() {
        // --- Event Listener Submit Form Edit Data Tekstual ---
        // Biarkan form edit siswa bekerja secara normal ke edit_siswa_post.php
        $("form#editAppsform").submit(function(event) {
            event.preventDefault();

            var postdata = new FormData(this);
            var postaction = $(this).attr('action');

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
                    if (response.status) {
                        responseDiv.html(
                            '<div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            response.message + '</div>'
                        );
                        // Tidak perlu menyembunyikan form di sini, hanya tampilkan pesan sukses
                    } else {
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

        // --- Event Listener untuk Tombol Perbarui Data Wajah ---
        $('#updateFaceDataBtn').click(function() {
            $("#editAppsform").hide(); // Sembunyikan form edit data tekstual
            $("#faceCaptureSection").show(); // Tampilkan bagian pengambilan wajah
            $("#captureInstructions").text(`Siap untuk memperbarui data wajah untuk: ${studentNameForFaceCapture}`);
            // Opsional: Langsung mulai kamera
            // $('#startWebcam').click(); 
        });


        // --- Logika Pengambilan Wajah via WebRTC dengan Deteksi ---

        $('#startWebcam').click(function() {
            if (!faceCascade) {
                $('#captureStatus').text('Error: Model deteksi wajah belum dimuat. Mohon tunggu atau refresh halaman.');
                return;
            }

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function(stream) {
                        webcamStream = stream;
                        videoElement.srcObject = stream;
                        videoElement.play();
                        $('#startWebcam').hide();
                        $('#stopWebcamButton').show(); // Tampilkan tombol stop
                        $('#captureInstructions').text(`Arahkan wajah Anda ke kamera. Mengambil ${totalCapturesNeeded} sampel.`);
                        
                        isCapturing = true; // Mulai proses capturing dan deteksi
                        captureCount = 0;
                        capturedImagesBase64 = [];
                        processVideoFrame(); // Mulai pemrosesan frame video
                    })
                    .catch(function(err) {
                        $('#captureStatus').text('Gagal mengakses kamera: ' + err.name + '. Pastikan kamera terhubung dan diizinkan.');
                        console.error('Error accessing webcam: ', err);
                    });
            } else {
                $('#captureStatus').text('Browser Anda tidak mendukung WebRTC untuk akses kamera. Gunakan browser modern.');
            }
        });

        // Fungsi untuk menghentikan webcam
        $('#stopWebcamButton').click(function() {
            stopWebcam();
            isCapturing = false; // Hentikan proses capturing
            $('#startWebcam').show();
            $('#stopWebcamButton').hide();
            $('#captureStatus').text('Kamera dihentikan.');
            // Tampilkan kembali form edit setelah stop
            $("#faceCaptureSection").hide();
            $("#editAppsform").show();
        });

        function stopWebcam() {
            if (webcamStream) {
                webcamStream.getTracks().forEach(track => track.stop());
                videoElement.srcObject = null;
            }
        }

        // Fungsi utama untuk memproses setiap frame video
        function processVideoFrame() {
            if (!isCapturing || !webcamStream || !videoElement.videoWidth) {
                return; // Berhenti jika tidak dalam mode capturing atau video tidak aktif
            }

            // Gambar frame video ke canvas untuk deteksi
            captureContext.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);

            // Buat objek Mat dari canvas untuk OpenCV.js
            let src = cv.imread(canvasElement);
            let gray = new cv.Mat();
            cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY, 0); // Ubah ke grayscale

            let faces = new cv.RectVector();
            let msize = new cv.Size(0, 0); // Ukuran minimal untuk deteksi
            
            // Deteksi wajah menggunakan Haar Cascade
            faceCascade.detectMultiScale(gray, faces, 1.1, 3, 0, msize, msize); // parameter tuning bisa disesuaikan

            // Gambar kotak di sekitar wajah yang terdeteksi
            for (let i = 0; i < faces.size(); ++i) {
                let face = faces.get(i);
                let point1 = new cv.Point(face.x, face.y);
                let point2 = new cv.Point(face.x + face.width, face.y + face.height);
                cv.rectangle(src, point1, point2, new cv.Scalar(0, 255, 0, 255), 2); // Kotak hijau
            }

            // Tampilkan frame yang sudah digambar kembali ke canvas
            cv.imshow(canvasElement, src);

            // Hapus objek Mat untuk menghindari kebocoran memori
            src.delete();
            gray.delete();
            faces.delete();

            // Hanya ambil sampel jika wajah terdeteksi dan belum mencapai batas
            if (faces.size() > 0 && captureCount < totalCapturesNeeded) {
                if (captureCount % 1 === 0) { // Ambil setiap frame deteksi
                    var imageData = canvasElement.toDataURL('image/jpeg', 0.9);
                    capturedImagesBase64.push(imageData);
                    captureCount++;
                    $('#captureStatus').text(`Wajah terdeteksi! Mengambil sampel: ${captureCount}/${totalCapturesNeeded}`);
                }
            } else if (faces.size() === 0) {
                $('#captureStatus').text('Mencari wajah... Pastikan wajah terlihat jelas.');
            }

            // Lanjutkan pemrosesan frame berikutnya
            if (captureCount < totalCapturesNeeded) {
                requestAnimationFrame(processVideoFrame);
            } else {
                stopWebcam();
                isCapturing = false;
                $('#captureStatus').text('Pengambilan wajah selesai. Mengirimkan data untuk diproses...');
                sendAllCapturedFaces();
            }
        }
        
        // Fungsi untuk mengirim semua gambar yang terkumpul ke PHP backend
        function sendAllCapturedFaces() {
            $('#captureStatus').text('Memproses dan mengirimkan wajah... Ini mungkin memakan waktu beberapa detik.');
            
            $.ajax({
                type: "POST",
                // URL ini harus menunjuk ke skrip PHP yang akan memproses update wajah (misalnya process_face_capture.php)
                url: "./app_python/process_face_capture.php", 
                data: {
                    nama: studentNameForFaceCapture, // Menggunakan nama siswa (siswa_nama di DB)
                    // Atau siswaId: studentIdForFaceCapture jika Anda ingin mengidentifikasi via ID (lebih disarankan)
                    // Jika menggunakan ID, pastikan process_face_capture.php juga diubah untuk menggunakan ID
                    images: capturedImagesBase64
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status) {
                        $("#response").html(
                            '<div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            response.message + '</div>'
                        );
                        // Kembali ke form edit siswa setelah sukses update wajah
                        $('#faceCaptureSection').hide();
                        $("#editAppsform").show();
                        $('#startWebcam').show();
                        $('#stopWebcamButton').hide();
                        
                        // Reset variabel
                        captureCount = 0; 
                        capturedImagesBase64 = [];
                        $('#captureStatus').text(''); 
                    } else {
                        $("#response").html(
                            '<div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            response.message + '</div>'
                        );
                        $('#startWebcam').show();
                        $('#stopWebcamButton').hide();
                        $('#captureStatus').text('Gagal memproses. Coba lagi atau periksa log server.');
                    }
                },
                error: function(jqXHR, textStatus, errorMessage) {
                    $("#response").html(
                        '<div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        'Error AJAX saat mengirim wajah: ' + errorMessage + '</div>');
                    $('#startWebcam').show();
                    $('#stopWebcamButton').hide();
                    $('#captureStatus').text('Kesalahan jaringan. Coba lagi.');
                },
                beforeSend: function() {
                    $('#captureStatus').text('Memproses dan mengirimkan wajah... Mohon tunggu.');
                }
            });
        }
    });
    </script>