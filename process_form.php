

<?php
include 'database.php';

// Membuat instance objek database
$db = new Database();

// Jika permintaan adalah untuk meng-update harga
if (isset($_POST['update_harga'])) {
    // Tangani ID dan harga baru
    $id = $_POST['id'];
    $harga_baru = $_POST['harga_baru'];

    // Panggil metode updateHarga untuk memperbarui harga
    $result = $db->updateHarga($id, $harga_baru);
    echo $result;
}
?>
