<?php
include 'db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $id = intval($id); // Amankan ID biar pasti angka
    $query = "UPDATE tugas SET status='Selesai' WHERE idTugas = $id";

    if ($conn->query($query)) {
        // Jika berhasil
        header("Location: tugas_views.php?filter=semua&message=Tugas berhasil diselesaikan&type=success");
    } else {
        // Jika gagal
        header("Location: tugas_views.php?filter=semua&message=Gagal menyelesaikan tugas&type=error");
    }
} else {
    // Kalau ID kosong
    header("Location: tugas_views.php?filter=semua&message=ID tugas tidak ditemukan&type=error");
}
exit();
?>
