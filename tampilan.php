<?php
session_start();

// Set waktu timeout dalam detik (30 menit)
$timeout = 1800; // 30 * 60 detik


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

// Cek apakah pengguna adalah admin
if ($_SESSION['nama_lengkap'] === 'admin') {
    // Jika admin, redirect ke halaman utama
    header("Location: index.php");
    exit; // Hentikan eksekusi skrip selanjutnya setelah redirect
}



// Cek apakah waktu sesi telah berakhir
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    // Jika ya, hapus semua data sesi
    session_unset();
    session_destroy();
    // Redirect ke halaman login
    header("Location: index.php");
    exit;
}

// Perbarui waktu aktivitas terakhir
$_SESSION['last_activity'] = time();


// Set cookie untuk memeriksa apakah browser masih terbuka
setcookie('browser_status', 'open', time() + $timeout, "/"); // Cookie berlaku selama waktu sesi
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

<?php


// Buat objek database
$db = new Database();

// Ambil semua data gambar dari database
$data_gambar = $db->getAllData();

// Koneksi ke database
$con = mysqli_connect("localhost", "id22040946_sela", "Sela1234@@", "id22040946_costume_maker");

// Periksa koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
}

// Variabel untuk pesan peringatan
$warning_message = "";

$sql = "SELECT * FROM costume";
$result = mysqli_query($con, $sql);

// Loop untuk menampilkan data
while ($row = mysqli_fetch_assoc($result)) {
    // Ambil data untuk tiap gambar
    $id_to_show = $row['id'];
    $nama_karakter = $row['Nama_Karakter'];
    $asal = $row['asal'];
    $foto = $row['foto'];
}
    // Tutup koneksi database
    mysqli_close($con);
    ?>

        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="#page-top"><img src="assets/img/logo.png " alt="..." /></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars ms-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                        <li class="nav-item"><a class="nav-link" href="#services">Top 3 Costume</a></li>
                        <li class="nav-item"><a class="nav-link" href="#more">More Costume</a></li>
                        <li class="nav-item"><a class="nav-link" href="#team">Pemesanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container">
                <div class="masthead-subheading">Welcome To Catalog Costume</div>
                <div class="masthead-heading text-uppercase">Nice To Meet You</div>
                <a class="btn btn-primary btn-xl text-uppercase" href="#services">Tell Me More</a>
            </div>
        </header>

  
<section class="page-section" id="services">
            <div class="container">
                <div class="text-center">
                    <br>
                    <br>
                    <br>
                    <h2 class="section-heading text-uppercase">Top 3 Costume</h2>
                    <h3 class="section-subheading text-muted">   </h3>
                </div>
                <div class="row text-center">
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"> </i>
                            <img src="assets/img/kafka (2).jpg" alt="" class="fa-stack-1x rounded-circle" style="width: 100%; height: 100%;">
                        </span>
                        <h4 class="my-3">kafka </h4>
                        <h4>(honkai star rail)</h4>
                        <p class="text-muted">Price = 800.000.00</p>
                        
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <img src="assets/img/boa.jpg" alt="" class="fa-stack-1x rounded-circle" style="width: 100%; height: 100%;">
                        </span>
                        <h4 class="my-3">Boa Hancock</h4>
                        <h4>(One Piece)</h4>
                        <p class="text-muted">Price = 1.000.000.00</p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <img src="assets/img/Esdeath.jpg" alt="" class="fa-stack-1x rounded-circle" style="width: 100%; height: 100%;">
                        </span>
                        <h4 class="my-3">Esdeath</h4>
                        <h4 class="my-3">Akame Ga Kill</h4>
                        <p class="text-muted">Price = 900.000.00</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Portfolio Grid-->
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
        <section class="page-section bg-light" id="team">
            <div class="container">
                <div class="text-center1">
                    <h2 class="section-heading text-uppercase">Pemesanan</h2>
                   
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="team-member">
                            <a href="https://wa.me/qr/6JQOJTL5675IA1" target="_blank">
                            <img class="mx-auto rounded-circle" src="assets/img/w.jpg" alt="WhatsApp QR Code">
                            </a>
                            <h4>Whatsap</h4>
                    
                        </div>
                    </div>
                    <div class="col-lg-4">
                            <h3 class="section-subheading text-muted">Pemesanan dapat di lakukan pada Kontak kami, info leboh lanjut terkait pemesanan silahkan hubungi kontak tertera</h3>
    
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Clients-->
        <!-- Contact-->
<section class="page-section1" id="contact">
    <div class="container">
        <div class="bg">
            <div class="text-center1">
                <h2 class="section-heading text-uppercase">Contact us</h2>
                <h3 class="section-subheading text-muted">Silahkan berikan rekomendasi costume rental yang diinginkan</h3>
            </div>
            <form id="contactForm" action="submit_form.php" method="post">
                <div class="row align-items-stretch mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <!-- Name input -->
                            <input class="form-control" name="name" id="name" type="text" placeholder="Your Name *" required />
                            <div class="invalid-feedback">A name is required.</div>
                        </div>
                        <div class="form-group">
                            <!-- Email address input -->
                            <input class="form-control" name="email" id="email" type="email" placeholder="Your Email *" required />
                            <div class="invalid-feedback">An email is required.</div>
                            <div class="invalid-feedback">Email is not valid.</div>
                        </div>
                        <div class="form-group mb-md-0">
                            <!-- Phone number input -->
                            <input class="form-control" name="phone" id="phone" type="tel" placeholder="Your Phone *" required />
                            <div class="invalid-feedback">A phone number is required.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-textarea mb-md-0">
                            <!-- Message input -->
                            <textarea class="form-control" name="message" id="message" placeholder="Your Message *" required></textarea>
                            <div class="invalid-feedback">A message is required.</div>
                        </div>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="text-center">
                    <button class="btn btn-primary btn-xl text-uppercase" type="submit">Send Message</button>
                </div>
            </form>
            <?php if(isset($success_message)): ?>
                <div class="text-center">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>


        <!-- Footer-->
        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-lg-start">Copyright &copy; Rental Costume</div>
                    <div class="col-lg-4 my-3 my-lg-0">
                        <a class="btn btn-dark btn-social mx-2" href="https://www.instagram.com/s_selaputri16" aria-label="Twitter"><i class="fa-brands fa-instagram"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="https://www.facebook.com/share/J8oFuYQDEM4dHrcw/?mibextid=qi2Omg" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a class="link-dark text-decoration-none me-3" href="#!">Owner</a>
                        <a class="link-dark text-decoration-none" href="#!">Sela Putri Ismalia</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Portfolio Modals-->
        <!-- Portfolio item 1 modal popup-->
        <?php
$database = new Database();
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
    <div class="portfolio-modal modal fade" id="portfolioModal<?php echo $id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <!-- Struktur modal -->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="close-modal" data-bs-dismiss="modal"><img src="assets/img/close-icon.svg" alt="Close modal" /></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="modal-body">
                                <!-- Informasi karakter -->
                                <h2 class="text-uppercase"><?php echo $nama_karakter; ?></h2>
                                <br>
                                <br>
                                <br>
                                <br>
                                <p class="item-intro text-muted"><?php echo $asal; ?></p>
                                <img class="img-fluid d-block mx-auto" src="data:image/jpeg;base64,<?php echo base64_encode($foto); ?>" alt="..." />
                                <!-- Informasi tambahan -->
                                <p>Harga: <?php echo $harga; ?></p>
                                <p>Waktu Pembuatan: <?php echo $waktu_pembuatan; ?></p>
                                <p>Ukuran: <?php echo $ukuran; ?></p>
                                <p>Bahan: <?php echo $bahan; ?></p>
                                <!-- Tombol Tutup -->
                                <button class="btn btn-primary btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                    <i class="fas fa-xmark me-1"></i>
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


        // <script>
        // // Fungsi untuk memperbarui waktu aktivitas setiap 5 detik
        // setInterval(function() {
        //     // Kirim permintaan AJAX ke server untuk memperbarui sesi
        //     var xhr = new XMLHttpRequest();
        //     xhr.open("GET", "update_session.php", true);
        //     xhr.send();
        // }, 5000); // Setiap 5 detik
        // </script>
        <!-- Portfolio item 2 modal popup-->
        <!--  -->
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!--<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>-->
        <!--    <script>-->
            // Function to logout the user via AJAX
        <!--    function logoutUser() {-->
        <!--        var xhr = new XMLHttpRequest();-->
        <!--        xhr.open("GET", "logout.php", true);-->
        <!--        xhr.send();-->
        <!--    }-->

            // Add event listener for when the user leaves the page or closes the tab/browser
        <!--    window.addEventListener("beforeunload", function(event) {-->
                // Call the logout function when the event occurs
        <!--        logoutUser();-->
        <!--    });-->
        <!--</script>-->

</body>
</html>
