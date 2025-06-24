<?php
include 'db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $conn->query("UPDATE tugas SET status='Selesai' WHERE idTugas = $id");
}
header("Location: tugas_views.php?filter=semua");
exit();
?>