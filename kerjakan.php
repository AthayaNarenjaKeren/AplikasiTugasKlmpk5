<?php
include 'db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $id = intval($id); // Amankan ID
    $query = "UPDATE tugas SET status='Sedang Diproses' WHERE idTugas = $id";

    if ($conn->query($query)) {
        header("Location: tugas_views.php?filter=semua&message=Tugas berhasil diproses&type=success");
    } else {
        header("Location: tugas_views.php?filter=semua&message=Gagal memproses tugas&type=error");
    }
} else {
    header("Location: tugas_views.php?filter=semua&message=ID tugas tidak ditemukan&type=error");
}
exit();
