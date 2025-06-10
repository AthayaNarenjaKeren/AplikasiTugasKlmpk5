<?php
include 'db.php'; // Mengandung session_start()

// Periksa apakah user sudah login, jika tidak, redirect ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data tugas dari database
$sql = "SELECT t.idTugas, t.judul, t.deadline, t.status, t.prioritas, k.NamaKategori
        FROM tugas t
        LEFT JOIN kategori k ON t.id_kategori = k.idKategori
        ORDER BY t.deadline ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Tugas Sederhana</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header-nav">
            <h2>Daftar Tugas</h2>
            <div class="links">
                <span>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="add.php">Tambah Tugas</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <?php
        if (isset($_GET['message'])) {
            $message_type = isset($_GET['type']) ? $_GET['type'] : 'success';
            echo "<p class='message {$message_type}'>" . htmlspecialchars($_GET['message']) . "</p>";
        }
        ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Prioritas</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["idTugas"]; ?></td>
                        <td><?php echo htmlspecialchars($row["judul"]); ?></td>
                        <td><?php echo $row["deadline"]; ?></td>
                        <td class='status-<?php echo strtolower(str_replace(' ', '-', $row["status"])); ?>'><?php echo htmlspecialchars($row["status"]); ?></td>
                        <td class='prioritas-<?php echo strtolower($row["prioritas"]); ?>'><?php echo htmlspecialchars($row["prioritas"]); ?></td>
                        <td><?php echo (isset($row["NamaKategori"]) ? htmlspecialchars($row["NamaKategori"]) : "Tidak Ada"); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">Tidak ada tugas yang ditemukan.</p>
        <?php endif; ?>
    </div>
    <?php $conn->close(); // Tutup koneksi di akhir ?>
</body>
</html>