<?php

session_start();

 // Memasukkan file database.php
include 'database.php';
$db = new Database();

// Setelah melakukan logout, arahkan pengguna kembali ke halaman index
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Hapus sesi dan redirect ke halaman login
    session_destroy();
    header("Location: index.php");
    exit;
}

// Periksa apakah session 'nik' dan 'nama_lengkap' telah di-set
if (!isset($_SESSION['nik']) || !isset($_SESSION['nama_lengkap'])) {
    // Jika tidak, redirect ke halaman login
    header("Location: index.php");
    exit; // Hentikan eksekusi skrip selanjutnya setelah redirect
}

// Jika sesi sudah di-set, cek apakah pengguna adalah admin
if ($_SESSION['nik'] === '12345' && $_SESSION['nama_lengkap'] === 'admin') {
    // Jika admin, lanjutkan eksekusi skrip
} else {
    // Jika bukan admin, redirect ke index.php
    header("Location: index.php");
    exit; // Hentikan eksekusi skrip selanjutnya setelah redirect
}


// Proses Formulir hanya jika data telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Jika permintaan adalah untuk menambahkan data baru
    if (isset($_POST['submit'])) {
        // Tangani data yang diterima dari formulir
        $nama_karakter = $_POST['nama_karakter'];
        $asal = $_POST['asal'];
    
        // Tangani gambar yang diunggah
        $foto = $_FILES['gambar']['tmp_name'];
        $foto_name = $_FILES['gambar']['name'];
        $foto_size = $_FILES['gambar']['size'];
    
        // Baca file gambar jika ada
        if ($foto_name != "") {
            $gambar_data = file_get_contents($foto);
        } else {
            $gambar_data = null; // Atur null jika tidak ada gambar yang diunggah
        }
    
        // Panggil metode tambahData untuk menambahkan data ke database
        if ($db->tambahData($nama_karakter, $asal, $gambar_data)) {
            // Ambil costume_id yang baru saja ditambahkan
            $success_message = "Data berhasil ditambahkan.";
        } else {
            $error_message = "Gagal menambahkan data.";
        }
    }
    
    // Lakukan pemrosesan form untuk menambah detail pembelian
    if (isset($_POST['submit_detail'])) {
        // Ambil nilai dari form
        $costume_id = $_POST['costume_id'];
        $harga = $_POST['harga'];
        $waktu_pembuatan = $_POST['waktu_pembuatan'];
        $ukuran = $_POST['ukuran'];
        $bahan = $_POST['bahan'];

        // Panggil metode detailCostumeExists untuk memeriksa apakah detail kostum sudah ada dalam database
        if ($db->detailCostumeExists($costume_id)) {
            // Jika detail kostum sudah ada, tampilkan pesan pop-up
            echo "<script>alert('Detail costume for this costume ID already exists.');</script>";
        } else {
            // Panggil metode tambahDetail untuk menambah detail pembelian ke database
            $result = $db->tambahDetail($harga, $waktu_pembuatan, $ukuran, $bahan, $costume_id);
            if ($result === true) {
                $success_message = "Data berhasil ditambahkan.";
            } else {
                $error_message = $result; // Menampilkan pesan kesalahan
            }
        }
    }
    
    // Jika permintaan adalah untuk meng-update harga
    if (isset($_POST['update_harga'])) {
        // Tangani ID dan harga baru
        $id = $_POST['id'];
        $harga_baru = $_POST['harga_baru'];

        // Panggil metode updateHarga untuk memperbarui harga
        $result = $db->updateHarga($id, $harga_baru);
        if ($result === true) {
            $success_message = "Harga berhasil diperbarui.";
        } else {
            $error_message = $result; // Menampilkan pesan kesalahan
        }
    }

    // Jika permintaan adalah untuk menghapus data
    if (isset($_POST['hapus'])) {
        // Tangani ID data yang akan dihapus
        $id_to_delete = $_POST['id_to_delete'];

        // Panggil metode hapusData untuk menghapus data dari database berdasarkan ID
        if ($db->hapusDataByID($id_to_delete)) {
            $success_message = "Data dengan ID $id_to_delete berhasil dihapus.";
        } else {
            $error_message = "Gagal menghapus data.";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Rental Costume</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
<body>
    <h2>Tambah Data</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="text" name="nama_karakter" placeholder="Nama Karakter" required><br>
        <input type="text" name="asal" placeholder="Asal" required><br>
        <input type="file" name="gambar" accept="image/*"><br> <!-- Hapus required jika tidak wajib -->
        <button type="submit" name="submit">Tambahkan</button> <!-- Tambahkan name="submit" -->
    </form>
    <?php if(isset($success_message)): ?>
        <script>
            alert("<?php echo $success_message; ?>");
        </script>
    <?php endif; ?>
    <?php if(isset($error_message)): ?>
        <script>
            alert("<?php echo $error_message; ?>");
        </script>
    <?php endif; ?>

    <h2>Tambah Data Costume</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <?php
        // Ambil daftar costume_id dari database
        $costume_ids = $db -> getAllCostumeIDs();
        ?>

        <select name="costume_id">
            <?php foreach ($costume_ids as $costume_id) : ?>
                <option value="<?php echo $costume_id; ?>"><?php echo $costume_id; ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <input type="text" name="harga" placeholder="Harga" required><br>
        <input type="text" name="waktu_pembuatan" placeholder="Waktu Pembuatan" required><br>
        <input type="text" name="ukuran" placeholder="Ukuran" required><br>
        <input type="text" name="bahan" placeholder="Bahan" required><br>

        <button type="submit" name="submit_detail">Tambahkan Detail Data</button>
    </form>

    <h2>Hapus Data</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <select name="id_to_delete" required>
        <?php
        // Ambil semua ID dari database
        $database = new Database;
        $all_ids = $database->getAllCostumeIDs();

        // Tampilkan opsi dropdown untuk setiap ID
        foreach ($all_ids as $id) {
            echo "<option value=\"$id\">$id</option>";
        }
        ?>
    </select>
    <button type="submit" name="hapus">Hapus Data</button> <!-- Tambahkan name="hapus" -->
</form>
<br><br><br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <button type="submit" name="logout">Logout</button>
</form>
<br><br><br><br><br><br><br><br>
<section class="page-section bg-light" id="more">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">More Costume</h2>
            <h3 class="section-subheading text-muted">All OF Costume</h3>
        </div>
        <div class="row">
            <?php
            // Koneksi ke database
            $con = mysqli_connect("localhost", "id22040946_sela", "Sela1234@@", "id22040946_costume_maker");

            // Periksa koneksi
            if (mysqli_connect_errno()) {
                echo "Koneksi database gagal: " . mysqli_connect_error();
            }

            // Query untuk mengambil semua data gambar dari database
            $sql = "SELECT * FROM costume";
            $result = mysqli_query($con, $sql);

            // Periksa apakah ada data gambar yang ditemukan
            if (mysqli_num_rows($result) > 0) {
                // Loop untuk menampilkan semua gambar
                while ($row = mysqli_fetch_assoc($result)) {
                    $id_to_show = $row['id'];
                    $nama_karakter = $row['Nama_Karakter'];
                    $asal = $row['asal'];
                    $foto = $row['foto'];
            ?>
                    <div class="col-lg-4 col-sm-6 mb-4">
                        <!-- Portfolio item -->
                        <div class="portfolio-item">
                            <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal<?php echo $id_to_show; ?>">
                                <div class="portfolio-hover">
                                    <div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                <!-- Ganti sumber gambar dengan gambar dari database -->
                                <br>
                                <br>
                                <br>
                                <br>
                                <img class="img-fluid" src="data:image/jpeg;base64,<?php echo base64_encode($foto); ?>" alt="..." />
                            </a>
                            <div  class="portfolio-caption">
                                <div class="portfolio-caption-heading"><?php echo $nama_karakter; ?></div>
                                <div class="portfolio-caption-subheading text-muted"><?php echo $asal; ?></div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                // Jika tidak ada gambar yang ditemukan, tampilkan pesan
                echo "Tidak ada gambar yang ditemukan.";
            }

            // Tutup koneksi database
            mysqli_close($con);
            ?>
        </div>
    </div>
</section>



<!-- Portfolio Modals-->
<?php
$database = new database;
$data = $database->getAllData();
foreach ($data as $row) {
    $id = $row['id'];
    $nama_karakter = $row['Nama_Karakter'];
    $asal = $row['asal'];
    $foto = $row['foto'];
    $harga = $row['harga']; // Mengambil harga dari database
    $waktu_pembuatan = $row['waktu_pembuatan']; // Mengambil waktu pembuatan dari database
    $ukuran = $row['ukuran']; // Mengambil ukuran dari database
    $bahan = $row['bahan']; // Mengambil bahan dari database

    // Konten modal
?>
    <div class="portfolio-modal modal fade" id="portfolioModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="close-modal" data-bs-dismiss="modal"><img src="assets/img/close-icon.svg" alt="Close modal" /></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="modal-body">
                                <h2 class="text-uppercase"><?php echo $row['Nama_Karakter']; ?></h2>
                                <p class="item-intro text-muted"><?php echo $row['asal']; ?></p>
                                <br>
                                <br>
                                <br>
                                <img class="img-fluid d-block mx-auto" src="data:image/jpeg;base64,<?php echo base64_encode($row['foto']); ?>" alt="..." />
                                <p>Harga: <span id="harga_<?php echo $row['id']; ?>"><?php echo $row['harga']; ?></span>
                                    <button class="btn btn-sm btn-primary" id="edit_button_<?php echo $row['id']; ?>" onclick="editHarga(<?php echo $row['id']; ?>)">Edit</button>
                                </p>
                                <p>Waktu Pembuatan: <?php echo $row['waktu_pembuatan']; ?></p>
                                <p>Ukuran: <?php echo $row['ukuran']; ?></p>
                                <p>Bahan: <?php echo $row['bahan']; ?></p>
                                <!-- Form untuk mengubah harga -->
                                <form id="formHarga_<?php echo $row['id']; ?>" style="display: none;">
                                    <input type="text" id="inputHarga_<?php echo $row['id']; ?>" value="<?php echo $row['harga']; ?>">
                                    <button type="button" class="btn btn-sm btn-success" onclick="simpanHarga(<?php echo $row['id']; ?>)">Simpan</button>
                                </form>
                                <button class="btn btn-primary btn-xl text-uppercase" data-bs-dismiss="modal" type="button">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<h2>Data Rekomendasi</h2>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Pesan</th>
        </tr>
    </thead>
    <tbody>
        <!-- PHP untuk menampilkan data dari tabel rekomendasi -->
        <?php
        // Memanggil metode untuk mendapatkan semua data rekomendasi
        $rekomendasiData = $db->getAllRekomendasi();

        // Loop untuk menampilkan setiap baris data rekomendasi
        foreach ($rekomendasiData as $rekomendasi) :
        ?>
            <tr>
                <td><?php echo $rekomendasi['id']; ?></td>
                <td><?php echo $rekomendasi['name']; ?></td>
                <td><?php echo $rekomendasi['email']; ?></td>
                <td><?php echo $rekomendasi['phone']; ?></td>
                <td><?php echo $rekomendasi['message']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<script>
    function editHarga(id) {
        // Simpan konten HTML tombol edit sebelum tombol tersebut dihapus
        var editButtonContent = document.getElementById("edit_button_" + id).outerHTML;
        document.getElementById("harga_" + id).style.display = "none";
        document.getElementById("edit_button_" + id).style.display = "none";
        document.getElementById("formHarga_" + id).style.display = "block";
        // Simpan konten HTML tombol edit ke dalam atribut data agar bisa diakses kembali
        document.getElementById("formHarga_" + id).setAttribute("data-edit-button-content", editButtonContent);
    }

    function simpanHarga(id) {
        var harga_baru = document.getElementById("inputHarga_" + id).value;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Tampilkan pesan sukses atau error
                alert(this.responseText);
                // Tampilkan kembali tombol edit setelah penyimpanan harga selesai
                var editButtonContent = document.getElementById("formHarga_" + id).getAttribute("data-edit-button-content");
                document.getElementById("harga_" + id).innerHTML = harga_baru + editButtonContent;
                document.getElementById("harga_" + id).style.display = "block";
                document.getElementById("edit_button_" + id).style.display = "inline-block"; // Ganti "block" dengan "inline-block" agar tombol edit tidak mengambil lebar penuh
                document.getElementById("formHarga_" + id).style.display = "none";
            }
        };
        xhttp.open("POST", "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("update_harga=true&id=" + id + "&harga_baru=" + harga_baru);
    }
</script>

<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="js/scripts.js"></script>
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<!-- * *                               SB Forms JS                               * *-->
<!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->

</body>
</html>
