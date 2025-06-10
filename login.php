<?php
include 'db.php'; // Mengandung session_start()

// Jika sudah login, redirect ke index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Proses form login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM pengguna WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Cek password (DEMO: tanpa hashing, ganti dengan password_verify() di produksi!)
        if ($password === $row['password']) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            $message = "Username atau password salah!";
            $message_type = "error";
        }
    } else {
        $message = "Username atau password salah!";
        $message_type = "error";
    }
    $stmt->close();
}
$conn->close(); // Tutup koneksi setelah selesai
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aplikasi Tugas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Login Aplikasi Tugas</h2>
        <?php if (isset($message)): ?>
            <p class="message <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['logged_out'])): ?>
            <p class="message success">Anda telah berhasil logout.</p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>