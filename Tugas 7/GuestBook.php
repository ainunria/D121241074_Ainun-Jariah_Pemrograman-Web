<?php
class GuestBook {
    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'db_perpus';
        $username = 'root';
        $password = '';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }

    public function tambahPesan($nama, $email, $pesan) {
        $stmt = $this->pdo->prepare("INSERT INTO buku_tamu (nama, email, pesan) VALUES (:nama, :email, :pesan)");
        return $stmt->execute([
            ':nama' => $nama,
            ':email' => $email,
            ':pesan' => $pesan
        ]);
    }

    public function ambilPesan() {
        $stmt = $this->pdo->query("SELECT * FROM buku_tamu ORDER BY tanggal_kirim DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>