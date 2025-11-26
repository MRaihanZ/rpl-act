<?php
// DB
$host = '127.0.0.1';
$port = '3306';
$dbname = 'db_mahasiswa';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// READ
$result = $conn->query("SELECT * FROM mahasiswa ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Mahasiswa</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        /* Print-specific optimization */
        @media print {
            body {
                margin: 20mm;
                font-size: 14px;
            }

            .no-print {
                display: none;
            }

            table {
                page-break-inside: avoid;
            }
        }
    </style>

</head>

<body>

    <div class="container">

        <!-- Header -->
        <div class="text-center my-4">
            <h1 class="fw-bold">Laporan</h1>
            <h3 class="fw-bold">Data Mahasiswa</h3>
            <p class="text-muted text-end">
                Tanggal: <span id="tanggal"></span>
            </p>
        </div>

        <!-- Table -->
        <table class="table">
            <thead class="table-primary">
                <tr>
                    <th style="width: 5%" scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">NPM</th>
                    <th scope="col">Tinggi</th>
                    <th scope="col">Pindahan</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example row, replace with backend loop -->
                <?php
                $index = 1;
                while ($row = $result->fetch_assoc()):
                    $oddEven = $index % 2 == 0 ? "table-secondary" : "";
                ?>
                    <tr class="<?= $oddEven ?>">
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= $row['npm'] ?></td>
                        <td><?= $row['tinggi'] ?></td>
                        <td><?= $row['pindahan'] ? 'Ya' : 'Tidak' ?></td>
                    </tr>
                <?php
                    $index++;
                endwhile;
                ?>

            </tbody>
        </table>

    </div>

    <script>
        // Set today's date in YYYY-MM-DD
        document.getElementById("tanggal").textContent =
            new Date().toISOString().split('T')[0];

        window.onload = function() {
            window.print();
            setTimeout(() => window.close(), 500);
        };
    </script>

</body>

</html>