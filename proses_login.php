<?php
session_start();

// Fungsi untuk membersihkan input
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Dapatkan data dari formulir dan bersihkan
$nik = clean($_POST['nik']);
$nama_lengkap = clean($_POST['nama_lengkap']);

// Baca file konfigurasi
$file = 'config.txt';
$lines = file($file, FILE_IGNORE_NEW_LINES);

// Inisialisasi variabel flag untuk menandai apakah pengguna ditemukan dalam file konfigurasi
$userFound = false;

// Loop melalui setiap baris dalam file konfigurasi
foreach ($lines as $line) {
    // Pisahkan data pada baris menggunakan delimiter "|"
    $data = explode('|', $line);

    // Periksa apakah ada entri dalam file konfigurasi yang sesuai dengan nilai yang diharapkan untuk admin
    if (count($data) === 2 && $data[0] === '12345' && strtolower($data[1]) === 'admin') {
        // Jika ditemukan, atur session dan arahkan pengguna ke admin.php
        if ($nik === '12345' && $nama_lengkap === 'admin') {
            $_SESSION['nik'] = '12345';
            $_SESSION['nama_lengkap'] = 'admin';
            header("Location: admin.php");
            exit; // Hentikan eksekusi skrip selanjutnya setelah redirect
        }
    }

    // Periksa apakah ada entri dalam file konfigurasi yang sesuai dengan nilai yang diharapkan untuk tampilan
    if (count($data) === 2 && strtolower($data[0]) === strtolower($nik) && strtolower($data[1]) === strtolower($nama_lengkap)) {
        // Jika ditemukan, atur session dan arahkan pengguna ke tampilan.php
        $_SESSION['nik'] = $nik;
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        header("Location: tampilan.php");
        exit; // Hentikan eksekusi skrip selanjutnya setelah redirect
    } else {
        // Jika pengguna tidak ditemukan, tetapkan $userFound ke false
        $userFound = false;
    }
}

// Jika tidak ditemukan dalam file konfigurasi atau tidak sesuai dengan kriteria admin, arahkan ke index.php dan tampilkan pesan kesalahan
?>
<script type="text/javascript">
    window.alert('Nama pengguna atau kata sandi salah');
    window.location.assign('index.php');
</script>
