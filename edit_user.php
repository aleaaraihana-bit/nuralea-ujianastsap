<?php

require_once __DIR__ . "/../includes/auth.php";

wajibAdmin();

$id = intval($_GET['id'] ?? 0);


// AMBIL DATA USER
$stmt = $conn->prepare(
    "SELECT * FROM users WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User tidak ditemukan.");
}


// PROSES UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $no_wa = trim($_POST['no_wa']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $nisn = trim($_POST['nisn']);
    $role = $_POST['role'];


    // VALIDASI NISN
    if (!preg_match('/^[0-9]{10}$/', $nisn)) {

        setNotif(
            "NISN harus tepat 10 angka.",
            "error"
        );

        header("Location: edit_user.php?id=" . $id);
        exit;
    }


    // VALIDASI NOMOR WHATSAPP
    if (!preg_match('/^[0-9]{10,15}$/', $no_wa)) {

        setNotif(
            "Nomor WhatsApp harus berupa angka 10-15 digit.",
            "error"
        );

        header("Location: edit_user.php?id=" . $id);
        exit;
    }


    // CEK USERNAME DAN NISN
    $cek = $conn->prepare(
        "SELECT id FROM users
         WHERE (username = ? OR nisn = ?)
         AND id != ?
         LIMIT 1"
    );

    $cek->bind_param(
        "ssi",
        $username,
        $nisn,
        $id
    );

    $cek->execute();

    $hasil = $cek->get_result();


    if ($hasil->num_rows > 0) {

        setNotif(
            "Username atau NISN sudah digunakan.",
            "error"
        );

        header("Location: edit_user.php?id=" . $id);
        exit;
    }


    // UPDATE DATA
    $update = $conn->prepare(
        "UPDATE users
         SET nama=?,
             username=?,
             no_wa=?,
             jenis_kelamin=?,
             nisn=?,
             role=?
         WHERE id=?"
    );

    $update->bind_param(
        "ssssssi",
        $nama,
        $username,
        $no_wa,
        $jenis_kelamin,
        $nisn,
        $role,
        $id
    );


    if ($update->execute()) {


        // UPDATE PASSWORD JIKA DIISI
        if (!empty($_POST['password'])) {

            $password = $_POST['password'];

            $pass = $conn->prepare(
                "UPDATE users SET password=? WHERE id=?"
            );

            $pass->bind_param(
                "si",
                $password,
                $id
            );

            $pass->execute();
        }


        // PESAN WHATSAPP
        $message =
            "Halo " . $nama . ",\n\n" .
            "Data akun kamu berhasil diperbarui oleh admin " .
            "di sistem UJIAN ASTS.\n\n" .
            "Silakan cek kembali data akun kamu.";


        // API WHATSAPP
        $apiUrl =
            "http://localhost/ujian_asts_website/api-wa/send.php";


        $postData = [
            "target" => $no_wa,
            "message" => $message
        ];


        $ch = curl_init($apiUrl);

        curl_setopt(
            $ch,
            CURLOPT_RETURNTRANSFER,
            true
        );

        curl_setopt(
            $ch,
            CURLOPT_POST,
            true
        );

        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            http_build_query($postData)
        );


        $response = curl_exec($ch);

        curl_close($ch);


        setNotif(
            "Data user berhasil diperbarui."
        );


        header("Location: data_users.php");
        exit;


    } else {

        setNotif(
            "Gagal memperbarui data user.",
            "error"
        );

        header("Location: edit_user.php?id=" . $id);
        exit;
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

    <title>Edit User</title>

    <link
        rel="stylesheet"
        href="../assets/style.css"
    >

</head>


<body>


<div class="container">


    <div class="card">


        <h1>Edit User</h1>


        <?php tampilNotif(); ?>


        <form method="POST">


            <label>Nama</label>

            <input
                type="text"
                name="nama"
                value="<?= htmlspecialchars($user['nama']) ?>"
                required
            >


            <label>Username</label>

            <input
                type="text"
                name="username"
                value="<?= htmlspecialchars($user['username']) ?>"
                required
            >


            <label>No. WhatsApp</label>

            <input
                type="text"
                name="no_wa"
                value="<?= htmlspecialchars($user['no_wa']) ?>"
                maxlength="15"
                pattern="[0-9]{10,15}"
                required
            >


            <label>Password Baru</label>

            <input
                type="password"
                name="password"
                placeholder="Kosongkan jika tidak ingin mengubah"
            >


            <label>Jenis Kelamin</label>

            <select
                name="jenis_kelamin"
                required
            >

                <option
                    value="Laki-laki"
                    <?= $user['jenis_kelamin'] === 'Laki-laki' ? 'selected' : '' ?>
                >
                    Laki-laki
                </option>

                <option
                    value="Perempuan"
                    <?= $user['jenis_kelamin'] === 'Perempuan' ? 'selected' : '' ?>
                >
                    Perempuan
                </option>

            </select>


            <label>NISN</label>

            <input
                type="text"
                name="nisn"
                maxlength="10"
                pattern="[0-9]{10}"
                value="<?= htmlspecialchars($user['nisn']) ?>"
                required
            >


            <label>Role</label>

            <select
                name="role"
                required
            >

                <option
                    value="user"
                    <?= $user['role'] === 'user' ? 'selected' : '' ?>
                >
                    User
                </option>

                <option
                    value="admin"
                    <?= $user['role'] === 'admin' ? 'selected' : '' ?>
                >
                    Admin
                </option>

            </select>


            <br><br>


            <button
                type="submit"
                class="btn"
            >
                Simpan Perubahan
            </button>


            <a
                href="data_users.php"
                class="btn btn-edit"
            >
                Batal
            </a>


        </form>


    </div>


</div>


</body>

</html>