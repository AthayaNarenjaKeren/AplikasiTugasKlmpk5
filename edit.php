<?php
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID tugas dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php?message=ID tugas tidak ditemukan&type=error");
    exit();
}

// Ambil data tugas
$stmt = $conn->prepare("SELECT * FROM tugas WHERE idTugas = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$tugas = $result->fetch_assoc();
$stmt->close();

if (!$tugas) {
    header("Location: index.php?message=Tugas tidak ditemukan&type=error");
    exit();
}

// Ambil data kategori untuk dropdown
$kategori_result = $conn->query("SELECT idKategori, NamaKategori FROM kategori ORDER BY NamaKategori ASC");

// Proses jika form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul = $_POST['judul'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];
    $prioritas = $_POST['prioritas'];
    $id_kategori = $_POST['id_kategori'] !== '' ? (int)$_POST['id_kategori'] : null;

    $sql = "UPDATE tugas SET judul=?, deadline=?, status=?, prioritas=?, id_kategori=? WHERE idTugas=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $judul, $deadline, $status, $prioritas, $id_kategori, $id);

    if ($stmt->execute()) {
        header("Location: index.php?message=Tugas berhasil diperbarui&type=success");
        exit();
    } else {
        echo "Gagal mengedit tugas: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="styles.css?v=<?= time(); ?>">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">TugasKu</div>
        <ul class="navbar-menu">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="tugas_views.php">Tugas</a></li>
            <li><a href="add.php">Tambah</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Edit Tugas</h2>
        <form method="POST">
            <label for="judul">Judul Tugas:</label>
            <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($tugas['judul']) ?>" required>

            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" value="<?= $tugas['deadline'] ?>" required>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="Belum Selesai" <?= $tugas['status'] == 'Belum Selesai' ? 'selected' : '' ?>>Belum Selesai</option>
                <option value="Selesai" <?= $tugas['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                <option value="Terlambat" <?= $tugas['status'] == 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
            </select>

            <label for="prioritas">Prioritas:</label>
            <select name="prioritas" id="prioritas" required>
                <option value="Rendah" <?= $tugas['prioritas'] == 'Rendah' ? 'selected' : '' ?>>Rendah</option>
                <option value="Sedang" <?= $tugas['prioritas'] == 'Sedang' ? 'selected' : '' ?>>Sedang</option>
                <option value="Tinggi" <?= $tugas['prioritas'] == 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
            </select>

            <label for="id_kategori">Kategori:</label>
            <select name="id_kategori" id="id_kategori">
                <option value="">-- Pilih Kategori --</option>
                <?php while ($row = $kategori_result->fetch_assoc()): ?>
                    <option value="<?= $row['idKategori'] ?>" <?= $tugas['id_kategori'] == $row['idKategori'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['NamaKategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input type="submit" value="Perbarui Tugas">
        </form>
    </div>
</body>
</html>
