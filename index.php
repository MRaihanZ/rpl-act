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

// CREATE
if (isset($_POST['create'])) {
    $nama = $_POST['nama'];
    $npm = (int)$_POST['npm'];
    $tinggi = (int)$_POST['tinggi'];
    $pindahan = isset($_POST['pindahan']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO mahasiswa (nama, npm, tinggi, pindahan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $nama, $npm, $tinggi, $pindahan);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
}

// DELETE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM mahasiswa WHERE id = $id");
    header("Location: index.php");
    exit;
}

// UPDATE
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $nama = $_POST['nama'];
    $npm = (int)$_POST['npm'];
    $tinggi = (int)$_POST['tinggi'];
    $pindahan = isset($_POST['pindahan']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE mahasiswa SET nama=?, npm=?, tinggi=?, pindahan=? WHERE id=?");
    $stmt->bind_param("siiii", $nama, $npm, $tinggi, $pindahan, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
}

// READ
$result = $conn->query("SELECT * FROM mahasiswa ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Simple CRUD Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <h3 class="text-center mb-4">CRUD Mahasiswa</h3>

        <form method="POST" id="createForm" class="card p-3 mb-4">
            <h5>Tambah Mahasiswa</h5>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>NPM</label>
                <input type="number" name="npm" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Tinggi (cm)</label>
                <input type="number" name="tinggi" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="pindahan" class="form-check-input" id="pindahan">
                <label class="form-check-label" for="pindahan">Pindahan</label>
            </div>
            <button type="submit" name="create" class="btn btn-primary w-100">Tambah</button>
        </form>

        <form method="POST" id="updateForm" class="card p-3 mb-4 hidden">
            <h5>Update Mahasiswa</h5>
            <input type="hidden" name="id" id="update_id">
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" id="update_nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>NPM</label>
                <input type="number" name="npm" id="update_npm" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Tinggi (cm)</label>
                <input type="number" name="tinggi" id="update_tinggi" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="pindahan" id="update_pindahan" class="form-check-input">
                <label class="form-check-label" for="update_pindahan">Pindahan</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="update" class="btn btn-success w-50">Simpan</button>
                <button type="button" class="btn btn-secondary w-50" onclick="toggleForms()">Batal</button>
            </div>
        </form>

        <div class="mb-3 text-end">
            <button class="btn btn-warning" onclick="printLaporan()">
                Print Laporan
            </button>
        </div>
        <table class="table table-bordered bg-white">
            <thead class="table-light text-center">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>NPM</th>
                    <th>Tinggi</th>
                    <th>Pindahan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="align-middle text-center">
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= $row['npm'] ?></td>
                        <td><?= $row['tinggi'] ?></td>
                        <td><?= $row['pindahan'] ? 'Ya' : 'Tidak' ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="editData(<?= htmlspecialchars(json_encode($row)) ?>)">Update</button>
                            <a href="?delete=<?= $row['id'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin hapus data ini?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleForms() {
            document.getElementById('createForm').classList.toggle('hidden');
            document.getElementById('updateForm').classList.toggle('hidden');
        }

        function editData(data) {
            toggleForms();
            document.getElementById('update_id').value = data.id;
            document.getElementById('update_nama').value = data.nama;
            document.getElementById('update_npm').value = data.npm;
            document.getElementById('update_tinggi').value = data.tinggi;
            document.getElementById('update_pindahan').checked = data.pindahan == 1;
        }

        function printLaporan() {
            // Open Page B in new tab/window
            window.open("report.php", "_blank");
        }
    </script>

</body>

</html>