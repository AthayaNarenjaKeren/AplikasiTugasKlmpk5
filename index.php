<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Aplikasi Tugas Harian</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2>📋 TugasKu</h2>
      <ul>
        <li><a href="#">Tugas Harian</a></li>
        <li><a href="#">Statistik</a></li>
        <li><a href="#">Pengaturan</a></li>
      </ul>
    </aside>

    <!-- Konten Utama -->
    <main class="main">
      <!-- Daftar Tugas -->
      <section class="task-list">
        <h1>Daftar Tugas Hari Ini</h1>
        <div class="task" onclick="showDetail('Belajar React', 'Pelajari hooks dan lifecycle')">
          <h3>📘 Belajar React</h3>
          <p>Pelajari hooks dan lifecycle</p>
        </div>
        <div class="task" onclick="showDetail('Olahraga', 'Jogging pagi selama 30 menit')">
          <h3>🏃 Olahraga</h3>
          <p>Jogging pagi selama 30 menit</p>
        </div>
        <div class="task" onclick="showDetail('Mengerjakan PR', 'Kerjakan PR matematika bab 5')">
          <h3>📝 Mengerjakan PR</h3>
          <p>Kerjakan PR matematika bab 5</p>
        </div>

        <!-- Tambah Tugas -->
        <div class="add-task">
          <h2>➕ Tambah Tugas Baru</h2>
          <input type="text" id="newTitle" placeholder="Judul Tugas" />
          <input type="text" id="newDesc" placeholder="Deskripsi Tugas" />
          <button onclick="addTask()">Tambah</button>
        </div>
      </section>

      <!-- Detail Tugas -->
      <section class="task-detail">
        <h2 id="detail-title">Pilih Tugas</h2>
        <p id="detail-desc">Klik salah satu tugas untuk melihat detailnya.</p>
      </section>
    </main>
  </div>

  <script>
    function showDetail(title, desc) {
      document.getElementById("detail-title").innerText = title;
      document.getElementById("detail-desc").innerText = desc;
    }

    function addTask() {
      const title = document.getElementById("newTitle").value;
      const desc = document.getElementById("newDesc").value;

      if (!title) return alert("Judul tidak boleh kosong!");

      const task = document.createElement("div");
      task.className = "task";
      task.onclick = () => showDetail(title, desc);
      task.innerHTML = <h3>🆕 ${title}</h3><p>${desc}</p>;

      document.querySelector(".task-list").insertBefore(task, document.querySelector(".add-task"));

      document.getElementById("newTitle").value = "";
      document.getElementById("newDesc").value = "";
    }
  </script>
</body>
</html>