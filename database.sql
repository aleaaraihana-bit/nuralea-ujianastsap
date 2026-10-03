CREATE DATABASE IF NOT EXISTS ujian_asts;

USE ujian_asts;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
    nisn CHAR(10) NOT NULL UNIQUE,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users
(nama, username, email, password, jenis_kelamin, nisn, role)
VALUES
(
    'Administrator',
    'admin',
    'admin@gmail.com',
    'admin123',
    'Laki-laki',
    '0000000001',
    'admin'
),
(
    'Alea',
    'alea',
    'alea@gmail.com',
    '12345678',
    'Perempuan',
    '1234567890',
    'user'
),
(
    'Nadia',
    'nadia',
    'nadia@gmail.com',
    '12345678',
    'Perempuan',
    '1234567891',
    'user'
),
(
    'Rizky',
    'rizky',
    'rizky@gmail.com',
    '12345678',
    'Laki-laki',
    '1234567892',
    'user'
);