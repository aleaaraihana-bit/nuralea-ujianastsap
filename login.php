<?php

session_start();

require_once "includes/config.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE username = ? LIMIT 1"
    );

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if ($password === $user['password']) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {

                header("Location: admin/dashboard.php");

            } else {

                header("Location: includes/user/dashboard.php");

            }

            exit;

        } else {

            $error = "Password salah.";

        }

    } else {

        $error = "Username tidak ditemukan.";

    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Login</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <div class="card" style="max-width:450px;margin:70px auto;">

        <h2>Login</h2>

        <?php if ($error): ?>

            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <label>Username</label>

            <input
                type="text"
                name="username"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                required
            >

            <button type="submit" class="btn">
                Login
            </button>

        </form>

        <br>

        <p>
            Belum punya akun?
            <a href="register.php">Register</a>
        </p>

    </div>

</div>

</body>
</html>