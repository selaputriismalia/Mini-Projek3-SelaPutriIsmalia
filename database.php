<?php
class Database {
    private $con;
    
    // Konstruktor untuk membuat koneksi ke database
    public function __construct() {
        $this->con =  mysqli_connect("localhost", "id22040946_sela", "Sela1234@@", "id22040946_costume_maker");
        // Periksa koneksi
        if (mysqli_connect_errno()) {
            echo "Koneksi database gagal: " . mysqli_connect_error();
            exit();
        }
    }
    
    // Fungsi untuk menambahkan data ke database
public function tambahData($nama_karakter, $asal, $gambar_data) {
    // Periksa apakah id sudah ada di dalam tabel
    $id = 1; // Id awal yang akan dicoba
    while ($this->idExists($id)) {
        $id++; // Coba id berikutnya jika sudah ada
    }

    // Query untuk menyimpan data ke database
    $sql = "INSERT INTO costume (id, Nama_Karakter, asal, foto) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($this->con, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $id, $nama_karakter, $asal, $gambar_data);
    mysqli_stmt_execute($stmt);

    // Periksa apakah data berhasil ditambahkan
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return true;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

// Fungsi untuk memeriksa apakah id sudah ada di dalam tabel
    private function idExists($id) {
        $stmt = mysqli_prepare($this->con, "SELECT id FROM costume WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $num_rows = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        return $num_rows > 0;
    }
    
     public function simpanData($name, $email, $phone, $message) {
        // Query untuk menyimpan data
        $sql = "INSERT INTO rekomendasi (name, email, phone, message) VALUES (?, ?, ?, ?)";
        
        // Persiapan statement
        $stmt = mysqli_prepare($this->con, $sql);
        
        // Bind parameter ke statement
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $phone, $message);
        
        // Eksekusi statement
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil, kembalikan ID terakhir yang disimpan
            return mysqli_insert_id($this->con);
        } else {
            // Jika gagal, kembalikan pesan error
            return "Error: " . $sql . "<br>" . mysqli_error($this->con);
        }
        
        // Tutup statement
        mysqli_stmt_close($stmt);
    }

    // Method untuk menutup koneksi
    public function tutupKoneksi() {
        $this->con->close();
    }
    
    // Fungsi untuk mendapatkan ID terakhir dari tabel costume
    public function getLastCostumeID() {
        $result = mysqli_query($this->con, "SELECT id FROM costume ORDER BY id DESC LIMIT 1");
        $row = mysqli_fetch_assoc($result);
        return ($row['id'] ?? 0) + 1; // Mengembalikan ID terakhir + 1, atau 1 jika tabel kosong
    }
    
    // Fungsi untuk mendapatkan semua ID kostum
    public function getAllCostumeIDs() {
        $query = "SELECT id FROM costume";
        $result = mysqli_query($this->con, $query);
        $costume_ids = array();
    
        // Ambil semua costume_id dan tambahkan ke dalam array
        while ($row = mysqli_fetch_assoc($result)) {
            $costume_ids[] = $row['id'];
        }
    
        return $costume_ids;
    }
   
 




    // Fungsi untuk mendapatkan ID terakhir dari tabel costume
    public function getLastInsertedID() {
        return mysqli_insert_id($this->con);
    }

    // Fungsi untuk menambahkan data detail costume ke tabel "detail_costume"
    public function tambahDetail($harga, $waktu_pembuatan, $ukuran, $bahan, $costume_id) {
        // Periksa apakah detail kostum sudah ada dalam database
        if ($this->detailCostumeExists($costume_id)) {
            return "Detail kostum sudah ada dalam database.";
        }

        // Jika detail kostum belum ada, tambahkan detail kostum baru
        $sql = "INSERT INTO detail_costume (harga, waktu_pembuatan, ukuran, bahan, costume_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $harga, $waktu_pembuatan, $ukuran, $bahan, $costume_id);
        mysqli_stmt_execute($stmt);

        // Periksa apakah data berhasil ditambahkan
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            return true;
        } else {
            return false;
        }

        mysqli_stmt_close($stmt);
    }
    public function getAllRekomendasi() {
    $query = "SELECT * FROM rekomendasi";
    $result = mysqli_query($this->con, $query); // Menggunakan properti $con untuk koneksi

    $rekomendasiData = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rekomendasiData[] = $row;
    }

    return $rekomendasiData;
    }
    // Fungsi untuk memeriksa apakah detail kostum sudah ada dalam database
    public function detailCostumeExists($costume_id) {
        $stmt = mysqli_prepare($this->con, "SELECT id FROM detail_costume WHERE costume_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $costume_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $num_rows = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        return $num_rows > 0;
    }

    // Metode lainnya ...

    // Fungsi untuk mendapatkan semua data gambar dari database
    public function getAllData() {
        $result = mysqli_query($this->con, "SELECT c.*, d.harga, d.waktu_pembuatan, d.ukuran, d.bahan FROM costume c JOIN detail_costume d ON c.id = d.costume_id");
        $data = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // Fungsi untuk mendapatkan data gambar berdasarkan ID
    public function getDataByID($id) {
        $stmt = mysqli_prepare($this->con, "SELECT * FROM costume WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);

        return $data;
    }
    
    
    
    // Metode lainnya ...

    public function hapusDataByID($id) {
        // Hapus entri terkait dari tabel detail_costume
        $stmt_detail = $this->con->prepare("DELETE FROM detail_costume WHERE costume_id = ?");
        $stmt_detail->bind_param("i", $id);
        $stmt_detail->execute();
        $stmt_detail->close();
        
        // Hapus entri dari tabel costume
        $stmt_costume = $this->con->prepare("DELETE FROM costume WHERE id = ?");
        $stmt_costume->bind_param("i", $id);
        if ($stmt_costume->execute()) {
            return true; // Penghapusan berhasil
        } else {
            return false; // Gagal menghapus data
        }
    }

    // Fungsi untuk mendapatkan ID terakhir dari tabel "costume"

}


?>
