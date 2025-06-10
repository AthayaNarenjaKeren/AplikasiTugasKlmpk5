<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - TugasKu</title>
  <link rel="stylesheet" href="login.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container single-page">
    <main class="form-wrapper">
      <h1>🔐 Masuk ke TugasKu</h1>
      <form action="proses_login.php" method="POST">
        <input type="text" name="username" placeholder="Nama pengguna" required />
        <input type="password" name="password" placeholder="Kata sandi" required />
        <button type="submit">Masuk</button>
      </form>

      <?php if (isset($_GET['error'])): ?>
        <p style="color:red;">Username atau password salah 😢</p>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
