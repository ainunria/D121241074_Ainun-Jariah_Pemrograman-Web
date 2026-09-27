<?php
session_start();
require_once 'Transaction.php';

// Inisialisasi saldo & riwayat transaksi jika belum ada
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 0.0;
}
if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

// Generate Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


$error = '';
$success = '';

// Proses Form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    
    // Validasi CSRF Token
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $error = 'Token CSRF tidak valid!';
    } else {
        $type = $_POST['type'] ?? '';
        $amountInput = $_POST['amount'] ?? '';

        // Validasi tipe transaksi menggunakan match
        $isValidType = match ($type) {
            'deposit', 'withdrawal' => true,
            default => false,
        };

        // Validasi jumlah transaksi sebagai angka desimal positif
        if (!$isValidType) {
            $error = 'Jenis transaksi tidak valid.';
        } elseif (!is_numeric($amountInput) || (float)$amountInput <= 0) {
            $error = 'Jumlah transaksi harus berupa angka desimal positif.';
        } else {
            $amount = (float)$amountInput;
            $transactionId = 'TX-' . uniqid();

            $transaction = new Transaction($transactionId, $type, $amount);

            if ($transaction->process()) {
                // Simpan riwayat transaksi
                $_SESSION['transactions'][] = [
                    'id' => $transaction->getId(),
                    'type' => $transaction->getType(),
                    'amount' => $transaction->getAmount(),
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                $success = 'Transaksi berhasil diproses!';
            } else {
                $error = 'Gagal memproses transaksi. Saldo tidak mencukupi!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; line-height: 1.6; }
        .card { border: 1px solid #ccc; padding: 20px; border-radius: 8px; max-width: 500px; margin-bottom: 20px; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .form-group { margin-bottom: 12px; }
        label { display: block; margin-bottom: 5px; }
        input[type="number"], select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

    <h2>Sistem Manajemen Keuangan</h2>

    <div class="card">
        <!-- Pencegahan XSS pada pencetakan saldo -->
        <h3>Sisa Saldo: Rp <?= htmlspecialchars(number_format($_SESSION['balance'], 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?></h3>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <!-- CSRF Token Protection -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

            <div class="form-group">
                <label for="type">Jenis Transaksi:</label>
                <select name="type" id="type" required>
                    <option value="deposit">Deposit</option>
                    <option value="withdrawal">Penarikan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Jumlah (Rp):</label>
                <input type="number" step="0.01" name="amount" id="amount" placeholder="0.00" required>
            </div>

            <button type="submit">Proses Transaksi</button>
        </form>
    </div>

    <div class="card">
        <h3>Riwayat Transaksi</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($_SESSION['transactions'])): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Belum ada transaksi.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($_SESSION['transactions'] as $tx): ?>
                        <tr>
                            <!-- Mencegah XSS pada riwayat transaksi -->
                            <td><?= htmlspecialchars($tx['id'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(ucfirst($tx['type']), ENT_QUOTES, 'UTF-8') ?></td>
                            <td>Rp <?= htmlspecialchars(number_format($tx['amount'], 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($tx['timestamp'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>