<?php
session_start();
require_once 'GuestBook.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div>
            <label>Nama:</label><br>
            <input type="text" name="nama">
        </div>
        <div>
            <label>Email:</label><br>
            <input type="email" name="email">
        </div>
        <div>
            <label>Pesan:</label><br>
            <textarea name="pesan"></textarea>
        </div>
        <button type="submit">Kirim Pesan</button>
    </form>
</body>
</html>