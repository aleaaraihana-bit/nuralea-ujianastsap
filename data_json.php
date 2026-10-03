<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "../includes/config.php";

$total = $conn
    ->query("SELECT COUNT(*) AS total FROM users")
    ->fetch_assoc()['total'];

$laki = $conn
    ->query("SELECT COUNT(*) AS total FROM users WHERE jenis_kelamin='Laki-laki'")
    ->fetch_assoc()['total'];

$perempuan = $conn
    ->query("SELECT COUNT(*) AS total FROM users WHERE jenis_kelamin='Perempuan'")
    ->fetch_assoc()['total'];

echo json_encode([
    "status" => true,
    "data" => [
        "total_user" => (int)$total,
        "laki_laki" => (int)$laki,
        "perempuan" => (int)$perempuan
    ]
], JSON_PRETTY_PRINT);