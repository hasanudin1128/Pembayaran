<?php
require_once 'TransferBank.php';
require_once 'EWallet.php';
require_once 'QRIS.php';
require_once 'COD.php';
require_once 'VirtualAccount.php';

$hasil_proses = "";
$hasil_struk = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nominal = $_POST['nominal'];
    $metode = $_POST['metode'];

    $pembayaran = null;

    if ($metode == "TransferBank") {
        $pembayaran = new TransferBank($nominal);
    } elseif ($metode == "EWallet") {
        $pembayaran = new EWallet($nominal);
    } elseif ($metode == "QRIS") {
        $pembayaran = new QRIS($nominal);
    } elseif ($metode == "COD") {
        $pembayaran = new COD($nominal);
    } elseif ($metode == "VirtualAccount") {
        $pembayaran = new VirtualAccount($nominal);
    }

    if ($pembayaran != null) {
        $hasil_proses = $pembayaran->prosesPembayaran();
        $hasil_struk = $pembayaran->cetakStruk();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pembayaran Online</title>
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f39c12;
            --bg-color: #f4f7f6;
            --text-color: #333;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        input[type="number"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box; /* Penting agar padding tidak merusak lebar */
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1rem;
        }

        button:hover {
            background-color: #357abd;
        }

        .result-card {
            margin-top: 2rem;
            padding: 1rem;
            background: #eef2f7;
            border-left: 5px solid var(--secondary-color);
            border-radius: 8px;
        }

        .struk-box {
            background: #fff;
            padding: 10px;
            border: 1px dashed #aaa;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.9rem;
            white-space: pre-wrap; /* Menjaga format baris baru dari PHP */
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            background: var(--secondary-color);
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Pembayaran Online</h2>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="nominal">Nominal Pembayaran (Rp)</label>
            <input type="number" id="nominal" name="nominal" placeholder="Contoh: 50000" min="1" required>
        </div>

        <div class="form-group">
            <label for="metode">Metode Pembayaran</label>
            <select id="metode" name="metode" required>
                <option value="" disabled selected>-- Pilih Metode --</option>
                <option value="TransferBank">Transfer Bank</option>
                <option value="EWallet">E-Wallet</option>
                <option value="QRIS">QRIS</option>
                <option value="COD">Cash On Delivery (COD)</option>
                <option value="VirtualAccount">Virtual Account</option>
            </select>
        </div>

        <button type="submit">Konfirmasi Pembayaran</button>
    </form>

    <?php if ($hasil_proses != ""): ?>
        <div class="result-card">
            <span class="status-badge">Status Berhasil</span>
            <p><strong>Pesan:</strong> <?= $hasil_proses ?></p>
            <hr>
            <p><strong>Struk Pembayaran:</strong></p>
            <div class="struk-box"><?= nl2br($hasil_struk) ?></div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>