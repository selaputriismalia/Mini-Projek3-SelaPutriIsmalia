<?php
error_reporting(0);
$nik = strtolower($_POST['nik']); // Mengonversi input username menjadi huruf kecil
$nama_lengkap = $_POST['nama_lengkap'];

// Cek apakah username adalah "admin"
if ($nik === 'admin') {
    ?>
    <script type="text/javascript">
        alert('Maaf, username "admin" tidak dapat digunakan untuk pendaftaran.');
        window.location.assign('register.php');
    </script>
    <?php
    exit; // Hentikan eksekusi skrip
}

// Cek apakah nik sudah terdaftar atau belum 
$data = file("config.txt", FILE_IGNORE_NEW_LINES);
foreach ($data as $value) {
    $pecah = explode("|", $value);
    if (strtolower($nik) == strtolower($pecah['0'])) { // Mengonversi nik yang sudah terdaftar menjadi huruf kecil
        $cek = true;
    }
}

if ($cek) { // Jika nik sudah terdaftar
    ?>
    <script type="text/javascript">
        alert('Maaf, password yang Anda gunakan sudah terdaftar.');
        window.location.assign('register.php');
    </script>
    <?php
} else { // Jika data tidak ditemukan
    // Buat format penyimpanan ke txt config
    $format = "\n$nik|$nama_lengkap";

    // Buka file config.txt
    $file = fopen('config.txt', 'a');
    fwrite($file, $format);

    // Tutup file
    fclose($file);
    ?>
    <script type="text/javascript">
        alert('Pendaftaran berhasil dilakukan');
        window.location.assign('index.php');
    </script>
<?php } ?>
