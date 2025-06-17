<?php
include 'db.php'; // Mengandung session_start()

// Periksa apakah user sudah login, jika tidak, redirect ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil kategori untuk dropdown
$categories_query = "SELECT idKategori, NamaKategori FROM kategori ORDER BY NamaKategori ASC";
$categories_result = $conn->query($categories_query);

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
    $judul = $_POST['judul'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];
    $prioritas = $_POST['prioritas'];
    $id_kategori = $_POST['id_kategori'];

    // Handle jika kategori tidak dipilih (NULL)
    if (empty($id_kategori)) {
        $id_kategori = NULL;
    }

    $sql = "INSERT INTO tugas (judul, deadline, status, prioritas, id_kategori) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $judul, $deadline, $status, $prioritas, $id_kategori);

    if ($stmt->execute()) {
        header("Location: index.php?message=Tugas berhasil ditambahkan!&type=success");
        exit();
    } else {
        $message = "Error: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}
$conn->close(); // Tutup koneksi
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas Baru</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header-nav">
            <h2>Tambah Tugas Baru</h2>
            <div class="links">
                <a href="index.php">Kembali ke Daftar Tugas</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <?php if ($message): ?>
            <p class="message <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="add.php" method="POST">
            <label for="judul">Judul Tugas:</label>
            <input type="text" id="judul" name="judul" required>

            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" required>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Belum Selesai">Belum Selesai</option>
                <option value="Selesai">Selesai</option>
                <option value="Terlambat">Terlambat</option>
            </select>

            <label for="prioritas">Prioritas:</label>
            <select id="prioritas" name="prioritas" required>
                <option value="Rendah">Rendah</option>
                <option value="Sedang">Sedang</option>
                <option value="Tinggi">Tinggi</option>
            </select>

            <label for="id_kategori">Kategori:</label>
            <select id="id_kategori" name="id_kategori">
                <option value="">-- Pilih Kategori --</option>
                <?php
                if ($categories_result->num_rows > 0) {
                    while ($row = $categories_result->fetch_assoc()) {
                        echo "<option value='" . $row['idKategori'] . "'>" . htmlspecialchars($row['NamaKategori']) . "</option>";
                    }
                }
                ?>
            </select>

            <input type="submit" value="Simpan Tugas">
        </form>
    </div>
</body>
</html>