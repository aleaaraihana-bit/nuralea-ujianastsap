<?php

require_once __DIR__ . "/../includes/auth.php";

wajibAdmin();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST["nama"]);
    $username = trim($_POST["username"]);
    $no_wa = trim($_POST["no_wa"]);
    $password = trim($_POST["password"]);
    $jenis_kelamin = $_POST["jenis_kelamin"];
    $nisn = trim($_POST["nisn"]);
    $role = $_POST["role"];


    // CEK NISN
    if (!preg_match('/^[0-9]{10}$/', $nisn)) {

        setNotif(
            "NISN harus tepat 10 angka.",
            "error"
        );


    // CEK NOMOR WHATSAPP
    } elseif (!preg_match('/^[0-9]{10,15}$/', $no_wa)) {

        setNotif(
            "Nomor WhatsApp harus berupa angka 10-15 digit.",
            "error"
        );


    } else {


        // CEK USERNAME DAN NISN
        // NOMOR WHATSAPP BOLEH SAMA

        $cek = $conn->prepare(
            "SELECT id FROM users
             WHERE username = ? OR nisn = ?
             LIMIT 1"
        );

        $cek->bind_param(
            "ss",
            $username,
            $nisn
        );

        $cek->execute();

        $hasil = $cek->get_result();


        if ($hasil->num_rows > 0) {

            setNotif(
                "Username atau NISN sudah digunakan.",
                "error"
            );


        } else {


            // SIMPAN USER

            $stmt = $conn->prepare(
                "INSERT INTO users
                (nama, username, password, jenis_kelamin, nisn, no_wa, role)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssssss",
                $nama,
                $username,
                $password,
                $jenis_kelamin,
                $nisn,
                $no_wa,
                $role
            );


            if ($stmt->execute()) {

                setNotif(
                    "User berhasil ditambahkan."
                );

                header("Location: data_users.php");

                exit;


            } else {

                setNotif(
                    "Gagal menambahkan user: " . $stmt->error,
                    "error"
                );

            }

        }

    }

}

?>


<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah User</title>

    <link
        rel="stylesheet"
        href="../assets/style.css"
    >

</head>


<body>


<div class="container">


    <?php tampilNotif(); ?>


    <div class="card">


        <h2>Tambah User</h2>


        <form method="POST">


            <!-- NAMA -->

            <label>Nama</label>

            <input
                type="text"
                name="nama"
                required
            >


            <!-- USERNAME -->

            <label>Username</label>

            <input
                type="text"
                name="username"
                required
            >


            <!-- NO WHATSAPP -->

            <label>No. WhatsApp</label>

            <input
                type="text"
                name="no_wa"
                placeholder="Contoh: 081234567890"
                maxlength="15"
                pattern="[0-9]{10,15}"
                required
            >


            <!-- PASSWORD -->

            <label>Password</label>

            <input
                type="password"
                name="password"
                required
            >


            <!-- JENIS KELAMIN -->

            <label>Jenis Kelamin</label>

            <select
                name="jenis_kelamin"
                required
            >

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


            <!-- NISN -->

            <label>NISN</label>

            <input
                type="text"
                name="nisn"
                maxlength="10"
                pattern="[0-9]{10}"
                required
            >


            <!-- ROLE -->

            <label>Role</label>

            <select
                name="role"
                required
            >

                <option value="user">
                    User
                </option>

                <option value="admin">
                    Admin
                </option>

            </select>


            <br><br>


            <button
                type="submit"
                class="btn"
            >
                Simpan
            </button>


            <a
                href="data_users.php"
                class="btn"
            >
                Batal
            </a>


        </form>


    </div>


</div>


</body>

</html>