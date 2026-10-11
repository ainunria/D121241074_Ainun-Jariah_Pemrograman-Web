<?php
session_start();
require_once 'GuestBook.php';

$guestbook = new GuestBook();
$errors = [];
$success = "";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Validasi CSRF gagal.");
    }

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    if (empty($nama)) {
        $errors[] = "Nama tidak boleh kosong.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }
    if (mb_strlen($pesan) < 5) {
        $errors[] = "Pesan minimal harus terdiri dari 5 karakter.";
    }

    if (empty($errors)) {
        if ($guestbook->tambahPesan($nama, $email, $pesan)) {
            $success = "Pesan berhasil dikirim!";
            $nama = $email = $pesan = "";
        } else {
            $errors[] = "Gagal menyimpan pesan ke database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Tamu Perpustakaan</title>
</head>
<body>
    <h2>Buku Tamu Perpustakaan</h2>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div>
            <label>Nama:</label><br>
            <input type="text" name="nama" value="<?= htmlspecialchars($nama ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div>
            <label>Pesan:</label><br>
            <textarea name="pesan"><?= htmlspecialchars($pesan ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <button type="submit">Kirim Pesan</button>
    </form>
</body>
</html>