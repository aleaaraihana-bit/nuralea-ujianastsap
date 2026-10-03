UJIAN ASTS WEBSITE
===================

Teknologi:
- PHP Native
- MySQL / MariaDB
- HTML
- CSS
- API JSON
- Fonnte WhatsApp API

CARA MENJALANKAN
================

1. Aktifkan Apache dan MySQL di XAMPP.

2. Simpan folder:
   ujian_asts_website

   ke:

   C:\xampp\htdocs\

3. Buka phpMyAdmin.

4. Buat/import database:

   database.sql

5. Pastikan database bernama:

   ujian_asts

6. Buka:

   http://localhost/ujian_asts_website/

AKUN ADMIN
==========

Username:
admin

Password:
admin123


AKUN USER
=========

Username:
alea

Password:
12345678


FITUR ADMIN
===========

- Dashboard
- Data Users
- Tambah User
- Edit User
- Notifications
- API Data User
- API Statistik


FITUR USER
==========

- Dashboard
- Melihat data akun
- Logout


API
===

Data user:

http://localhost/ujian_asts_website/api/users.php

Statistik:

http://localhost/ujian_asts_website/api/data_json.php


FONNTE
======

File:

includes/fonnte.php

Masukkan token Fonnte pada:

$token = "ISI_TOKEN_FONNTE_DI_SINI";


CATATAN
=======

NISN harus tepat 10 digit.

Role tersedia:
- admin
- user