
<?php
include 'db.php'; // Koneksi database & session

// Ambil data dari tiga view
$sql_selesai = "SELECT * FROM view_tugas_selesai";
$sql_proses = "SELECT * FROM view_tugas_diproses";
$sql_belum = "SELECT * FROM view_tugas_belum_selesai";

$selesai = $conn->query($sql_selesai);
$proses = $conn->query($sql_proses);
$belum = $conn->query($sql_belum);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Tugas Berdasarkan Status</title>
    <link rel="stylesheet" href="styles.css">
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

        <!-- BELUM SELESAI -->
        <h3 class="status-belum-selesai">Belum Selesai</h3>
        <table>
            <tr>
                <th>Nama Tugas</th>
                <th>Deadline</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $belum->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['deadline']) ?></td>
                    <td class="status-belum-selesai"><?= htmlspecialchars($row['status']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- DIPROSES -->
        <h3 class="status-terlambat">Sedang Diproses</h3>
        <table>
            <tr>
                <th>Nama Tugas</th>
                <th>Deadline</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $proses->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['NamaTugas']) ?></td>
                    <td><?= htmlspecialchars($row['Deadline']) ?></td>
                    <td class="status-terlambat"><?= htmlspecialchars($row['Status']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- SELESAI -->
        <h3 class="status-selesai">Selesai</h3>
        <table>
            <tr>
                <th>Nama Tugas</th>
                <th>Deadline</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $selesai->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['NamaTugas']) ?></td>
                    <td><?= htmlspecialchars($row['Deadline']) ?></td>
                    <td class="status-selesai"><?= htmlspecialchars($row['Status']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

    </div>
</body>

</html>
