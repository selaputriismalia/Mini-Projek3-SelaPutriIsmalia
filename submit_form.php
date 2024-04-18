<?php
session_start();

// Cek apakah pengguna sudah masuk ke dalam sesi login
if (!isset($_SESSION['nik']) || !isset($_SESSION['nama_lengkap'])) {
    // Jika tidak, redirect ke halaman login
    header("Location: index.php");
    exit; // Hentikan eksekusi skrip selanjutnya setelah redirect
}

// Include kelas Database
require_once "database.php";

// Pesan sukses
$success_message = "";

// Mengambil data dari formulir jika metode adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi dan ambil data dari formulir
    $name = validateInput($_POST['name']);
    $email = validateInput($_POST['email']);
    $phone = validateInput($_POST['phone']);
    $message = validateInput($_POST['message']);

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Validasi nomor telepon (hanya angka)
    if (!ctype_digit($phone)) {
        echo "Phone number must contain only digits";
        exit;
    }

    // Membuat objek Database
    $database = new Database();

    // Menyimpan data ke database
    $newId = $database->simpanData($name, $email, $phone, $message);

    // Menutup koneksi
    $database->tutupKoneksi();

    // Pesan sukses
    $success_message = "Pesan berhasil dikirim!";
    
    // Redirect ke tampilan.php setelah 2 detik
    header("refresh:2;url=tampilan.php");
}

// Fungsi untuk memvalidasi input
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Rekomendasi</title>
</head>
<body>
    <?php if (!empty($success_message)) : ?>
        <script>
            alert("<?php echo $success_message; ?>");
        </script>
    <?php endif; ?>

    <script>
        // Redirect ke tampilan.php setelah 2 detik
        setTimeout(function() {
            window.location.href = "tampilan.php";
        }, 150);
    </script>
</body>
</html>
