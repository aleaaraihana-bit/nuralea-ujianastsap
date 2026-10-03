<?php

require_once __DIR__ . "/../includes/auth.php";

wajibAdmin();

$query = $conn->query(
    "SELECT * FROM users ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Data Users</title>

    <link
        rel="stylesheet"
        href="../assets/style.css"
    >

    <style>

        .aksi {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-edit,
        .btn-danger {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-edit {
            background: #8b5e3c;
            color: white;
        }

        .btn-edit:hover {
            background: #6f472d;
        }

        .btn-danger {
            background: #b23a48;
            color: white;
        }

        .btn-danger:hover {
            background: #8f2d38;
        }

        .role-admin {
            background: #8b5e3c;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .role-user {
            background: #eee;
            color: #555;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

    </style>

</head>


<body>


<nav class="navbar">

    <div class="brand">
        UJIAN ASTS
    </div>


    <div class="nav-right">

        <span>
            Halo, <?= htmlspecialchars($_SESSION['nama']) ?>
        </span>

        <a
            href="../logout.php"
            class="btn-logout"
        >
            Logout
        </a>

    </div>

</nav>


<div class="container">


    <!-- HEADER -->

    <div class="card">

        <h2>Data Users</h2>


        <?php tampilNotif(); ?>


        <br>


        <a
            href="dashboard.php"
            class="btn"
        >
            Dashboard
        </a>


        <a
            href="tambah_user.php"
            class="btn"
        >
            + Tambah User
        </a>

    </div>



    <!-- TABEL -->

    <div class="card">

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Nama</th>

                        <th>Username</th>

                        <th>No. WhatsApp</th>

                        <th>Jenis Kelamin</th>

                        <th>NISN</th>

                        <th>Role</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    <?php

                    $no = 1;

                    if ($query && $query->num_rows > 0):

                        while ($user = $query->fetch_assoc()):

                    ?>

                    <tr>

                        <td>
                            <?= $no++ ?>
                        </td>


                        <td>
                            <?= htmlspecialchars($user['nama']) ?>
                        </td>


                        <td>
                            <?= htmlspecialchars($user['username']) ?>
                        </td>


                        <td>
                            <?= htmlspecialchars($user['no_wa']) ?>
                        </td>


                        <td>
                            <?= htmlspecialchars($user['jenis_kelamin']) ?>
                        </td>


                        <td>
                            <?= htmlspecialchars($user['nisn']) ?>
                        </td>


                        <td>

                            <?php if ($user['role'] === 'admin'): ?>

                                <span class="role-admin">
                                    Admin
                                </span>

                            <?php else: ?>

                                <span class="role-user">
                                    User
                                </span>

                            <?php endif; ?>

                        </td>


                        <td>

                            <div class="aksi">


                                <!-- EDIT -->

                                <a
                                    href="edit_user.php?id=<?= $user['id'] ?>"
                                    class="btn-edit"
                                >
                                    Edit
                                </a>


                                <!-- DELETE -->

                                <?php if ($user['id'] != $_SESSION['user_id']): ?>

                                    <a
                                        href="hapus_user.php?id=<?= $user['id'] ?>"
                                        class="btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus user ini?')"
                                    >
                                        Delete
                                    </a>

                                <?php else: ?>

                                    <span
                                        style="font-size:13px;color:#777;"
                                    >
                                        Akun sendiri
                                    </span>

                                <?php endif; ?>


                            </div>

                        </td>

                    </tr>


                    <?php

                        endwhile;

                    else:

                    ?>


                    <tr>

                        <td
                            colspan="8"
                            style="text-align:center;"
                        >
                            Belum ada data user.
                        </td>

                    </tr>


                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>


</div>


</body>

</html>