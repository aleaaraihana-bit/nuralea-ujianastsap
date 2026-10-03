<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/config.php";

function sudahLogin()
{
    return isset($_SESSION['user_id']);
}

function wajibLogin()
{
    if (!sudahLogin()) {
        header("Location: ../login.php");
        exit;
    }
}

function wajibAdmin()
{
    wajibLogin();

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../index.php");
        exit;
    }
}

function wajibUser()
{
    wajibLogin();

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
        header("Location: ../../admin/dashboard.php");
        exit;
    }
}

/* =========================
   NOTIFIKASI
========================= */

function setNotif($pesan, $tipe = "success")
{
    $_SESSION['notif'] = [
        'pesan' => $pesan,
        'tipe' => $tipe
    ];
}

function tampilNotif()
{
    if (isset($_SESSION['notif'])) {

        $notif = $_SESSION['notif'];

        $class = ($notif['tipe'] === 'error')
            ? 'alert-error'
            : 'alert';

        echo '<div class="' . $class . '">'
            . htmlspecialchars($notif['pesan'])
            . '</div>';

        unset($_SESSION['notif']);
    }
}