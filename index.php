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
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Tugas Sederhana</title>
    <link rel="stylesheet" href="styles.css?v=<?= time() ?>">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">TugasKu</div>
        <ul class="navbar-menu">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="tugas_views.php">Tugas</a></li>
            <li><a href="add.php">Tambah</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Pesan Sukses/Gagal -->
    <?php if (isset($_GET['message'])): ?>
        <?php $message_type = $_GET['type'] ?? 'success'; ?>
        <p class="message <?= $message_type ?>"><?= htmlspecialchars($_GET['message']) ?></p>
    <?php endif; ?>

    <!-- Tabel Daftar Tugas -->
    <?php if ($result->num_rows > 0): ?>
        <div class="tabel-container">
            <!-- Tambah kolom Aksi -->
            <table>
                <thead>
                    <tr>
                        <th>Nama Tugas</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Aksi</th> <!-- Tambahan -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) {
                        $judul = htmlspecialchars($row['judul'] ?? $row['NamaTugas']);
                        $deadline = htmlspecialchars($row['deadline'] ?? $row['Deadline']);
                        $status = htmlspecialchars($row['status'] ?? $row['Status']);
                        $class = strtolower(str_replace(' ', '-', $status));
                        $idTugas = $row['idTugas'] ?? $row['id'] ?? null;
                        ?>
                        <tr>
                            <td><?= $judul ?></td>
                            <td><?= $deadline ?></td>
                            <td class="status-<?= $class ?>"><?= $status ?></td>
                            <td>
                                <?php if ($status !== "Selesai"): ?>
                                    <a href="selesaikan.php?id=<?= $idTugas ?>" class="btn-selesai">Selesai</a>
                                <?php endif; ?>
                                <a href="hapus.php?id=<?= $idTugas ?>" onclick="return confirm('Yakin ingin menghapus?')"
                                    class="btn-hapus">Hapus</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    <?php else: ?>
        <p style="text-align: center;">Tidak ada tugas yang ditemukan.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>

</html>