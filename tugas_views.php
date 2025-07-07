<?php
include 'db.php'; // Koneksi database & session

$filter = $_GET['filter'] ?? 'semua';

if ($filter === 'belum') {
    $query = "SELECT * FROM view_tugas_belum_selesai";
} elseif ($filter === 'proses') {
    $query = "SELECT * FROM view_tugas_diproses";
} elseif ($filter === 'selesai') {
    $query = "SELECT * FROM view_tugas_selesai";
} else {
    $query = "SELECT * FROM tugas"; // Semua
    // Statistik hanya dihitung jika filter = semua
    $jumlah_tugas = $conn->query("SELECT COUNT(*) AS total FROM tugas")->fetch_assoc()['total'];
    $jumlah_selesai = $conn->query("SELECT COUNT(*) AS total FROM tugas WHERE status = 'Selesai'")->fetch_assoc()['total'];
    $jumlah_belum = $conn->query("SELECT COUNT(*) AS total FROM tugas WHERE status = 'Belum Selesai'")->fetch_assoc()['total'];
    $jumlah_terlambat = $conn->query("SELECT COUNT(*) AS total FROM tugas WHERE status = 'Terlambat'")->fetch_assoc()['total'];
    $deadline_terdekat = $conn->query("SELECT MIN(deadline) AS min_deadline FROM tugas")->fetch_assoc()['min_deadline'];
    $deadline_terjauh = $conn->query("SELECT MAX(deadline) AS max_deadline FROM tugas")->fetch_assoc()['max_deadline'];
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Tugas</title>
    <link rel="stylesheet" href="styles.css?v=<?= time(); ?>">
</head>

<body>

    <!-- Navbar Utama -->
    <nav class="navbar">
        <div class="navbar-brand">TugasKu</div>
        <ul class="navbar-menu">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Submenu Filter -->
    <div class="filter-navbar">
        <a href="tugas_views.php?filter=semua" class="<?= $filter === 'semua' ? 'active' : '' ?>">Semua</a>
        <a href="tugas_views.php?filter=belum" class="<?= $filter === 'belum' ? 'active' : '' ?>">Belum Selesai</a>
        <a href="tugas_views.php?filter=proses" class="<?= $filter === 'proses' ? 'active' : '' ?>">Sedang Diproses</a>
        <a href="tugas_views.php?filter=selesai" class="<?= $filter === 'selesai' ? 'active' : '' ?>">Selesai</a>
    </div>

    <div class="container">
        <h2 class="judul-halaman">Daftar Tugas <?= $filter !== 'semua' ? '(' . ucfirst($filter) . ')' : '' ?></h2>

        <?php if ($filter === 'semua') { ?>
            <div class="statistik-box">
                <h3>📊 Statistik Tugas</h3>
                <ul>
                    <li>Total Tugas: <strong><?= $jumlah_tugas ?></strong></li>
                    <li>Selesai: <strong><?= $jumlah_selesai ?></strong></li>
                    <li>Belum Selesai: <strong><?= $jumlah_belum ?></strong></li>
                    <li>Terlambat: <strong><?= $jumlah_terlambat ?></strong></li>
                    <li>Deadline Terdekat: <strong><?= $deadline_terdekat ?></strong></li>
                    <li>Deadline Terjauh: <strong><?= $deadline_terjauh ?></strong></li>
                </ul>
            </div>
        <?php } ?>

        <table>
            <tr>
                <th>Nama Tugas</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) {
                $id = $row['idTugas'] ?? $row['id'] ?? null;
                $judul = htmlspecialchars($row['judul'] ?? $row['NamaTugas']);
                $deadline = htmlspecialchars($row['deadline'] ?? $row['Deadline']);
                $status = htmlspecialchars($row['status'] ?? $row['Status']);
                $class = strtolower(str_replace(' ', '-', $status));
                ?>
                <tr>
                    <td><?= $judul ?></td>
                    <td><?= $deadline ?></td>
                    <td class="status-<?= $class ?>"><?= $status ?></td>
                    <td>
                        <a href="edit.php?id=<?= $id ?>" class="btn-aksi edit">Edit</a>

                        <?php if ($status === "Belum Selesai") { ?>
                            <a href="kerjakan.php?id=<?= $id ?>&filter=<?= $filter ?>" class="btn-aksi proses">Kerjakan</a>
                        <?php } ?>

                        <?php if ($status !== "Selesai") { ?>
                            <a href="selesaikan.php?id=<?= $id ?>&filter=<?= $filter ?>" class="btn-aksi selesai">Selesai</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>

</html>