<?php

require_once "includes/config.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $nisn = trim($_POST['nisn']);
    $no_wa = trim($_POST['no_wa']);

    // Cek NISN
    if (!preg_match('/^[0-9]{10}$/', $nisn)) {

        $error = "NISN harus tepat 10 digit.";

    // Cek nomor WhatsApp
    } elseif (!preg_match('/^[0-9]{10,15}$/', $no_wa)) {

        $error = "Nomor WhatsApp harus berupa angka 10-15 digit.";

    } else {

        // Cek username dan NISN
        // Nomor WhatsApp TIDAK dicek agar bisa digunakan berkali-kali
        $cek = $conn->prepare(
            "SELECT id, username, nisn
             FROM users
             WHERE username = ? OR nisn = ?
             LIMIT 1"
        );

        $cek->bind_param(
            "ss",
            $username,
            $nisn
        );

        $cek->execute();

        $result = $cek->get_result();

        if ($result->num_rows > 0) {

            $data = $result->fetch_assoc();

            if ($data['username'] === $username) {

                $error = "Username sudah digunakan.";

            } elseif ($data['nisn'] === $nisn) {

                $error = "NISN sudah digunakan.";

            } else {

                $error = "Data sudah digunakan.";

            }

        } else {

            // Simpan user
            // TIDAK ADA EMAIL
            $stmt = $conn->prepare(
                "INSERT INTO users
                (nama, username, password, jenis_kelamin, nisn, no_wa, role)
                VALUES (?, ?, ?, ?, ?, ?, 'user')"
            );

            $stmt->bind_param(
                "ssssss",
                $nama,
                $username,
                $password,
                $jenis_kelamin,
                $nisn,
                $no_wa
            );

            if ($stmt->execute()) {

                /*
                ==================================
                KIRIM NOTIFIKASI WHATSAPP
                ==================================
                */

                $url_api = "http://localhost/ujian_asts_website/api-wa/send.php";

                $pesan = "REGISTRASI BERHASIL\n\n"
                       . "Halo $nama,\n"
                       . "Akun kamu berhasil didaftarkan.\n\n"
                       . "Username: $username\n"
                       . "NISN: $nisn\n\n"
                       . "Silakan login ke website UJIAN ASTS.";

                $data_wa = [
                    "target" => $no_wa,
                    "message" => $pesan
                ];

                $curl = curl_init();

                curl_setopt_array($curl, [

                    CURLOPT_URL => $url_api,

                    CURLOPT_RETURNTRANSFER => true,

                    CURLOPT_POST => true,

                    CURLOPT_POSTFIELDS => http_build_query($data_wa),

                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/x-www-form-urlencoded'
                    ],

                    CURLOPT_TIMEOUT => 10

                ]);

                $response_wa = curl_exec($curl);

                $error_wa = curl_error($curl);

                curl_close($curl);

                if ($error_wa) {

                    $success = "Registrasi berhasil, tetapi notifikasi WhatsApp gagal dikirim.";

                } else {

                    $success = "Registrasi berhasil. Notifikasi WhatsApp sudah dikirim.";

                }

            } else {

                $error = "Registrasi gagal: " . $stmt->error;

            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <div class="card" style="max-width:600px;margin:40px auto;">

        <h2>Register User</h2>

        <?php if ($error): ?>

            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <?php if ($success): ?>

            <div class="alert">
                <?= htmlspecialchars($success) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <label>Nama</label>

            <input
                type="text"
                name="nama"
                required
            >

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

            <label>Jenis Kelamin</label>

            <select name="jenis_kelamin" required>

                <option value="">
                    -- Pilih --
                </option>

                <option value="Laki-laki">
                    Laki-laki
                </option>

                <option value="Perempuan">
                    Perempuan
                </option>

            </select>

            <label>NISN</label>

            <input
                type="text"
                name="nisn"
                maxlength="10"
                pattern="[0-9]{10}"
                required
            >

            <label>No. WhatsApp</label>

            <input
                type="text"
                name="no_wa"
                placeholder="Contoh: 081234567890"
                maxlength="15"
                pattern="[0-9]{10,15}"
                required
            >

            <button type="submit" class="btn">
                Register
            </button>

        </form>

        <br>

        <a href="login.php">
            Sudah punya akun? Login
        </a>

    </div>

</div>

</body>

</html>