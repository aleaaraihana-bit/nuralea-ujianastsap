<?php

require_once "../auth.php";

wajibUser();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Dashboard User</title>

    <link rel="stylesheet" href="../../assets/style.css">

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Dashboard User 👋</h1>

        <p>
            Selamat datang,
            <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>
        </p>

    </div>

    <div class="card">

        <h2>Data Akun</h2>

        <p>
            Username:
            <?= htmlspecialchars($_SESSION['username']) ?>
        </p>

        <p>
            Role:
            <span class="badge">
                <?= htmlspecialchars($_SESSION['role']) ?>
            </span>
        </p>

        <br>

        <a href="../../logout.php" class="btn btn-danger">
            Logout
        </a>

    </div>

</div>

</body>
</html>