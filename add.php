<?php
session_start(); // Harus paling atas!
include 'db.php';

// 🔁 Fungsi buat generate kode tugas
function generateKodeTugas($conn) {
    $conn->query("LOCK TABLES sequence_tugas WRITE"); // Kunci table sementara
    $result = $conn->query("SELECT last_number FROM sequence_tugas WHERE id = 1 FOR UPDATE");
    $row = $result->fetch_assoc();
    $last = $row['last_number'];
    $next = $last + 1;
    $conn->query("UPDATE sequence_tugas SET last_number = $next WHERE id = 1");
    $conn->query("UNLOCK TABLES");
    return "TGS" . str_pad($next, 4, "0", STR_PAD_LEFT);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil kategori
$categories_query = "SELECT idKategori, NamaKategori FROM kategori ORDER BY NamaKategori ASC";
$categories_result = $conn->query($categories_query);

$message = '';
$message_type = '';

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];
    $prioritas = $_POST['prioritas'];
    $id_kategori = $_POST['id_kategori'];
    $kode_tugas = generateKodeTugas($conn); // 🔑 Panggil kode baru

    if ($id_kategori === '') {
        $sql = "INSERT INTO tugas (kode_tugas, judul, deadline, status, prioritas, id_kategori) VALUES (?, ?, ?, ?, ?, NULL)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $kode_tugas, $judul, $deadline, $status, $prioritas);
    } else {
        $sql = "INSERT INTO tugas (kode_tugas, judul, deadline, status, prioritas, id_kategori) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $kode_tugas, $judul, $deadline, $status, $prioritas, $id_kategori);
    }

    if ($stmt->execute()) {
        header("Location: index.php?message=Tugas berhasil ditambahkan!&type=success");
        exit();
    } else {
        $message = "Gagal menambahkan tugas: " . $stmt->error;
        $message_type = "error";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tugas Baru</title>
    <link rel="stylesheet" href="styles.css?v=<?= time() ?>">
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

    <?php if ($message): ?>
        <p class="message <?= $message_type ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="container">
        <h2>Tambah Tugas Baru</h2>
        <form method="POST" action="add.php">
            <label for="judul">Judul Tugas:</label>
            <input type="text" name="judul" id="judul" required>

            <label for="deadline">Deadline:</label>
            <input type="date" name="deadline" id="deadline" required>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="Belum Selesai">Belum Selesai</option>
                <option value="Selesai">Selesai</option>
                <option value="Terlambat">Terlambat</option>
            </select>

            <label for="prioritas">Prioritas:</label>
            <select name="prioritas" id="prioritas" required>
                <option value="Rendah">Rendah</option>
                <option value="Sedang">Sedang</option>
                <option value="Tinggi">Tinggi</option>
            </select>

            <label for="id_kategori">Kategori:</label>
            <select name="id_kategori" id="id_kategori">
                <option value="">-- Pilih Kategori --</option>
                <?php while ($row = $categories_result->fetch_assoc()): ?>
                    <option value="<?= $row['idKategori'] ?>"><?= htmlspecialchars($row['NamaKategori']) ?></option>
                <?php endwhile; ?>
            </select>

            <input type="submit" value="Simpan Tugas">
        </form>
    </div>
</body>
</html>