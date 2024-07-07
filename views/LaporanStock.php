<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../config/koneksi.php"; 

function getAllData($conn, $table) {
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

function getDataByDateRange($conn, $table, $tanggal_awal, $tanggal_akhir) {
    $sql = "SELECT * FROM $table WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

if (isset($_GET['reset'])) {
    $tanggal_awal = '';
    $tanggal_akhir = '';
}

if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $foodData = getDataByDateRange($conn, 'tbl_stockfood', $tanggal_awal, $tanggal_akhir);
    $drinkData = getDataByDateRange($conn, 'tbl_stockdrink', $tanggal_awal, $tanggal_akhir);
    $coffeeData = getDataByDateRange($conn, 'tbl_stockcoffe', $tanggal_awal, $tanggal_akhir);
} else {
    $foodData = getAllData($conn, 'tbl_stockfood');
    $drinkData = getAllData($conn, 'tbl_stockdrink');
    $coffeeData = getAllData($conn, 'tbl_stockcoffe');
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        h1 {
            text-align: center;
        }
        .form-inline {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .form-group {
            margin: 0.5rem;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
            form, .btn {
                display: none !important;
            }
            table {
                -webkit-print-color-adjust: exact;
            }
        }
        .table-title {
            margin-top: 20px;
            font-size: 1.5em;
            font-weight: bold;
        }
        .center-logo {
            text-align: center;
            margin-bottom: 10px;
        }
        .center-logo img {
            max-width: 250px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="center-logo">
        <img src="logo.png" alt="Logo">
    </div>
    <h1 class="mt-3">Laporan Stok</h1>
    <br>
    <form action="" method="GET" class="form-inline mb-3">
        <label for="tanggal_awal" class="mr-2">Filter Tanggal</label>
        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control mr-2" value="<?php echo $tanggal_awal; ?>">
        
        <label for="tanggal_akhir" class="mr-2">Sampai</label>
        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control mr-2" value="<?php echo $tanggal_akhir; ?>">
        
        <button type="submit" class="btn btn-primary mr-2">Filter</button>
        
        <button type="submit" name="reset" class="btn btn-secondary mr-2">Reset</button>
        
        <div class="print-button">
            <button class="btn btn-primary" onclick="window.print()">Cetak Laporan</button>
        </div>
    </form>

    <div class="table-title">Food</div>
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Makanan</th>
                <th>Harga</th>
                <th>Stock</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($foodData as $row) { ?>
                <tr>
                    <td><?php echo $row['No']; ?></td>
                    <td><?php echo $row['Makanan']; ?></td>
                    <td><?php echo $row['harga']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td><?php echo $row['tanggal']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="table-title">Drink</div>
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Minuman</th>
                <th>Harga</th>
                <th>Stock</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($drinkData as $row) { ?>
                <tr>
                    <td><?php echo $row['No']; ?></td>
                    <td><?php echo $row['Minuman']; ?></td>
                    <td><?php echo $row['harga']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td><?php echo $row['tanggal']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="table-title">Coffee</div>
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Coffee</th>
                <th>Harga</th>
                <th>Stock</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($coffeeData as $row) { ?>
                <tr>
                    <td><?php echo $row['No']; ?></td>
                    <td><?php echo $row['Coffe']; ?></td>
                    <td><?php echo $row['harga']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td><?php echo $row['tanggal']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
