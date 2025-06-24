<?php
include 'db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $conn->query("DELETE FROM tugas WHERE idTugas = $id");
}
header("Location: tugas_views.php?filter=semua");
exit();
?>
