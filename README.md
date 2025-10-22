# Sistem Presensi RFID dan Foto Wajah ESP32CAM

Sistem presensi otomatis menggunakan teknologi RFID dan pengenalan wajah berbasis ESP32CAM untuk tracking kehadiran siswa secara real-time.

## 📋 Deskripsi

Sistem ini dirancang untuk mengelola presensi siswa secara otomatis dengan kombinasi teknologi RFID card dan pengenalan wajah menggunakan ESP32CAM. Sistem terdiri dari aplikasi web PHP untuk admin panel dan perangkat keras ESP untuk capture data presensi.

## ✨ Fitur Utama

- **Presensi RFID**: Scanning kartu RFID untuk identifikasi siswa
- **Pengenalan Wajah**: Capture foto wajah menggunakan ESP32CAM
- **Manajemen Siswa**: Tambah, edit, hapus data siswa
- **Manajemen Kelas**: Pengelolaan data kelas dan jadwal
- **Dashboard Admin**: Monitoring presensi real-time
- **Rekap Presensi**: Laporan kehadiran harian/bulanan
- **Validasi Jadwal**: Presensi berdasarkan jadwal masuk/pulang

## 🛠️ Teknologi yang Digunakan

### Backend
- **PHP 5.6+**: Bahasa pemrograman utama
- **MySQL**: Database management system
- **Bootstrap 4**: Framework CSS untuk UI

### Frontend
- **JavaScript/jQuery**: Interaksi client-side
- **HTML5/CSS3**: Struktur dan styling
- **Canvas API**: Processing gambar kamera

### Hardware
- **ESP32CAM**: Modul kamera untuk capture foto
- **RFID Reader**: Pembaca kartu RFID
- **ESP8266**: Mikrokontroler untuk RFID

## 📋 Persyaratan Sistem

### Software Requirements
- PHP 5.6 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web Server (Apache/Nginx)
- Browser modern dengan WebRTC support

### Hardware Requirements
- ESP32CAM module
- RFID Reader (MFRC522)
- LCD Display 16x2 (untuk ESP8266)
- Kartu RFID
- Kabel jumper dan breadboard

## 🚀 Instalasi dan Setup

### 1. Clone Repository
```bash
git clone https://github.com/username/sistem-presensi-rfid-dan-foto-wajah-esp32cam.git
cd sistem-presensi-rfid-dan-foto-wajah-esp32cam
```

### 2. Setup Database
```bash
# Import database schema
mysql -u root -p < sql/labandroid_esprfid.sql
```

### 3. Konfigurasi Database
Edit file `config/db.php`:
```php
$host     = "localhost";
$username = "root";  // Ganti dengan username database Anda
$password = "";      // Ganti dengan password database Anda
$database = "camrfidok"; // Nama database
```

### 4. Upload ke Web Server
Upload semua file ke direktori web server Anda (misalnya `/var/www/html/` atau `htdocs/`).

### 5. Setup Hardware ESP32CAM
1. Install Arduino IDE
2. Install ESP32 board support
3. Upload kode `code/cam.ino` ke ESP32CAM
4. Konfigurasi WiFi credentials di kode

### 6. Setup Hardware ESP8266 RFID
1. Install board support ESP8266 di Arduino IDE
2. Upload kode `code/final1.ino` ke ESP8266
3. Hubungkan RFID reader ke pin yang sesuai

## ⚙️ Konfigurasi

### WiFi Configuration
Edit credentials WiFi di file `code/cam.ino` dan `code/final1.ino`:
```cpp
const char* ssid = "Nama_WiFi_Anda";
const char* password = "Password_WiFi_Anda";
```

### Server Configuration
Update server URL di kode ESP:
```cpp
String serverName = "http://your-domain.com"; // Ganti dengan domain Anda
```

## 📖 Cara Penggunaan

### Akses Admin Panel
1. Buka browser dan akses `http://localhost/index.php`
2. Login dengan credentials default:
   - Username: `admin`
   - Password: `admin`

### Manajemen Data
1. **Data Siswa**: Tambah/edit/hapus data siswa dengan RFID dan foto
2. **Data Kelas**: Kelola informasi kelas
3. **Jadwal**: Setup jadwal masuk/pulang per kelas
4. **Rekap**: Monitor dan export laporan presensi

### Presensi Siswa
1. Siswa tap kartu RFID ke reader
2. Sistem capture foto wajah otomatis
3. Data presensi tersimpan dengan timestamp

## 📁 Struktur Proyek

```
sistem-presensi-rfid-dan-foto-wajah-esp32cam/
├── absen.php              # Halaman presensi siswa
├── index.php              # Dashboard admin
├── auth/                  # Sistem autentikasi
│   ├── login.php
│   └── login_post.php
├── config/                # Konfigurasi database
│   ├── db.php
│   └── function.php
├── assets/                # CSS, JS, Images
├── code/                  # Kode Arduino ESP
│   ├── cam.ino           # ESP32CAM
│   └── final1.ino        # ESP8266 RFID
├── sql/                   # Database schema
├── data_*.php            # CRUD operations
├── process_absen.php     # API presensi
├── cam_save.php          # Upload foto
└── esp.php               # API untuk ESP
```

## 🗄️ Skema Database

### Tabel Utama
- **admin**: Data administrator
- **siswa**: Data siswa (RFID, nama, kelas, foto)
- **kelas**: Data kelas
- **jadwal**: Jadwal masuk/pulang per kelas
- **rekap**: Record presensi harian
- **rfid_code**: Data kartu RFID

## 🔧 Troubleshooting

### Masalah Umum
1. **Kamera tidak berfungsi**: Periksa koneksi ESP32CAM dan WebRTC support browser
2. **RFID tidak terdeteksi**: Cek koneksi pin RFID reader
3. **Database error**: Verifikasi konfigurasi di `config/db.php`
4. **Upload foto gagal**: Pastikan folder `rekapfoto/` memiliki permission write

### Debug Mode
Aktifkan error reporting di PHP:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 🤝 Contributing

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Kontak

- Project Link: [https://github.com/username/sistem-presensi-rfid-dan-foto-wajah-esp32cam](https://github.com/username/sistem-presensi-rfid-dan-foto-wajah-esp32cam)
- Email: your-email@example.com

## 🙏 Acknowledgments

- [ESP32CAM Library](https://github.com/espressif/esp32-camera)
- [MFRC522 RFID Library](https://github.com/miguelbalboa/rfid)
- [Bootstrap](https://getbootstrap.com/)
- [jQuery](https://jquery.com/)

---

**Catatan**: Pastikan semua hardware terhubung dengan benar dan konfigurasi WiFi sesuai sebelum deploy ke production.
