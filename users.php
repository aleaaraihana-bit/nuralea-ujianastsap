<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "../includes/config.php";

$result = $conn->query(
    "SELECT id, nama, username, email, jenis_kelamin, nisn, role
     FROM users
     ORDER BY id DESC"
);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => true,
    "jumlah" => count($data),
    "data" => $data
], JSON_PRETTY_PRINT);