<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/config.php";

$nama = $_SESSION['nama'] ?? 'Guest';
$role = $_SESSION['role'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UJIAN ASTS</title>

    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<nav class="navbar">
    <div class="brand">
        UJIAN ASTS
    </div>

    <div class="nav-right">

        <?php if (isset($_SESSION['user_id'])): ?>

            <span>
                Halo, <?= htmlspecialchars($nama) ?>
            </span>

            <?php if ($role === 'admin'): ?>
                <a href="../admin/dashboard.php">Dashboard</a>
            <?php else: ?>
                <a href="../includes/user/dashboard.php">Dashboard</a>
            <?php endif; ?>

            <a href="../logout.php" class="btn-logout">
                Logout
            </a>

        <?php else: ?>

            <a href="../login.php">Login</a>
            <a href="../register.php">Register</a>

        <?php endif; ?>

    </div>
</nav>

<div class="container">