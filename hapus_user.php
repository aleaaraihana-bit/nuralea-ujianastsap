<?php

require_once __DIR__ . "/../includes/auth.php";

wajibAdmin();


// CEK ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    setNotif(
        "ID user tidak valid.",
        "error"
    );

    header("Location: data_users.php");
    exit;
}


$id = (int) $_GET['id'];


// TIDAK BOLEH HAPUS AKUN SENDIRI
if ($id === (int) $_SESSION['user_id']) {

    setNotif(
        "Kamu tidak bisa menghapus akun sendiri.",
        "error"
    );

    header("Location: data_users.php");
    exit;
}


// AMBIL DATA USER SEBELUM DIHAPUS
$getUser = $conn->prepare(
    "SELECT nama, no_wa FROM users WHERE id = ?"
);

$getUser->bind_param("i", $id);

$getUser->execute();

$result = $getUser->get_result();

$user = $result->fetch_assoc();


if (!$user) {

    setNotif(
        "User tidak ditemukan.",
        "error"
    );

    header("Location: data_users.php");
    exit;
}


$nama = $user['nama'];
$no_wa = $user['no_wa'];


// HAPUS USER
$stmt = $conn->prepare(
    "DELETE FROM users WHERE id = ?"
);

$stmt->bind_param("i", $id);


if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {


        // PESAN WHATSAPP
        $message =
            "Halo " . $nama . ",\n\n" .
            "Data akun kamu telah dihapus oleh admin " .
            "dari sistem UJIAN ASTS.\n\n" .
            "Terima kasih.";


        // KIRIM KE API WA
        $apiUrl = "http://localhost/ujian_asts_website/api-wa/send.php";


        $postData = [
            "target" => $no_wa,
            "message" => $message
        ];


        $ch = curl_init($apiUrl);


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            http_build_query($postData)
        );


        $response = curl_exec($ch);

        curl_close($ch);


        setNotif(
            "Data user berhasil dihapus."
        );


    } else {

        setNotif(
            "User tidak ditemukan.",
            "error"
        );

    }

} else {

    setNotif(
        "Gagal menghapus user.",
        "error"
    );

}


header("Location: data_users.php");

exit;