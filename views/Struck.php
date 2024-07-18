<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../config/koneksi.php";
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Struk Pembelian</title>

    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
            background-color: #f9f9f9;
        }

        .invoice-box {
            max-width: 312px;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
        }

        .center-logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .center-logo img {
            max-width: 150px;
        }
        .info {
            font-size: 14px;
            text-align: left;
        }

        .item {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .item div {
            margin-bottom: 3px;
        }

        .total {
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: left;
        }
        .total div {
            margin-bottom: 3px;
        }

        .footer {
            font-size: 12px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="invoice-box">
        <div class="center-logo">
            <img src="logo.png" alt="Logo">
        </div>
        <div class="info">
        <div style="width: 100px; display: inline-block; margin-bottom: 5px;">Kasir</div>
        <div style="display: inline-block;">: Daffa</div>
        <br>
        <div style="width: 100px; display: inline-block; margin-bottom: 5px;">NPM</div>
        <div style="display: inline-block;">: 22552011102</div>
        <br>
        <?php
        $NoOrder = mysqli_real_escape_string($conn, $_GET['co']);
        $customer_result = mysqli_query($conn, "SELECT * FROM tbl_penjualan WHERE NoOrder = '$NoOrder' LIMIT 1");
        if ($customer_result && mysqli_num_rows($customer_result) > 0) {
            $customer_data = mysqli_fetch_assoc($customer_result);
        } else {
            $customer_data = ['Pelayanan' => '', 'Pelanggan' => ''];
        }
        ?>
         <div style="width: 100px; display: inline-block; margin-bottom: 5px;">Pelayanan</div>
        <div style="display: inline-block;">: <?php echo htmlspecialchars($customer_data['Pelayanan']); ?></div>
        <br>
        <div style="width: 100px; display: inline-block; margin-bottom: 5px;">Pelanggan</div>
         <div style="display: inline-block;">: <?php echo htmlspecialchars($customer_data['Pelanggan']); ?></div>
        <br>
        <div style="width: 100px; display: inline-block; margin-bottom: 5px;">Tanggal</div>
        <div style="display: inline-block;">: <?php echo date('Y-m-d'); ?></div>
    </div>
        <div class="item">
            <div style="width: 100px; display: inline-block;">No. Order</div>
            <div style="display: inline-block;">: <?php echo htmlspecialchars($_GET['co']); ?></div>
            <hr>
            <div style="margin-bottom: 10px;">
                <div style="display: inline-block; width: 120px;"><strong>Nama Makanan</strong></div>
                <div style="display: inline-block; width: 60px;"><strong>Harga</strong></div>
                <div style="display: inline-block; width: 60px;"><strong>&nbsp;Jumlah</strong></div>
                <div style="display: inline-block; width: 60px;"><strong>&nbsp;Total</strong></div>
            </div>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM tbl_penjualan WHERE NoOrder = '$NoOrder'");
            if ($result && mysqli_num_rows($result) > 0) {
                while ($r = mysqli_fetch_array($result)) {
            ?>
                    <div>
                        <div style="display: inline-block; width: 120px;"><?php echo htmlspecialchars($r['Makanan']); ?></div>
                        <div style="display: inline-block; width: 60px; text-align: left;"><?php echo htmlspecialchars($r['harga']); ?></div>
                        <div style="display: inline-block; width: 60px; text-align: center;"><?php echo htmlspecialchars($r['jumlah']); ?></div>
                        <div style="display: inline-block; width: 60px; text-align: left;"><?php echo htmlspecialchars($r['total']); ?></div>
                    </div>
            <?php
                }
            } else {
                echo '<div>Data tidak ditemukan.</div>';
            }
            ?>
            <hr>
        </div>

        <?php
        $result = mysqli_query($conn, "SELECT SUM(total) as total, (bayar) as bayar, (kembali) as kembalian FROM tbl_penjualan WHERE NoOrder = '$NoOrder'");
        $totals = mysqli_fetch_assoc($result);
        $grand_total = $totals['total'] ?? 0;
        $bayar_amount = $totals['bayar'] ?? 0;
        $kembalian_amount = $totals['kembalian'] ?? 0;
        $ppn = 0.08 * $grand_total;
        $total_plus_ppn = $grand_total + $ppn;
        ?>

<div class="total">
<table>
                <tr>
                    <td style="width: 100px;">Total</td>
                    <td style="width: 10px;">:</td>
                    <td style="width: 100px;"><?php echo htmlspecialchars($grand_total); ?></td>
                </tr>
                <tr>
                    <td style="width: 100px;">PPN (8%)</td>
                    <td style="width: 10px;">:</td>
                    <td style="width: 100px;"><?php echo htmlspecialchars($ppn); ?></td>
                </tr>
                <tr>
                    <td style="width: 100px;">Total + PPN</td>
                    <td style="width: 10px;">:</td>
                    <td style="width: 100px;"><?php echo htmlspecialchars($total_plus_ppn); ?></td>
                </tr>
                <tr>
                    <td style="width: 100px;">Bayar</td>
                    <td style="width: 10px;">:</td>
                    <td style="width: 100px;"><?php echo htmlspecialchars($bayar_amount); ?></td>
                </tr>
                <tr>
                    <td style="width: 100px;">Kembali</td>
                    <td style="width: 10px;">:</td>
                    <td style="width: 100px;"><?php echo htmlspecialchars($kembalian_amount); ?></td>
                </tr>
            </table>
</div>
<br>  
        <div class="footer">Terima kasih atas pembelian Anda</div>
    </div>

</body>

</html>
