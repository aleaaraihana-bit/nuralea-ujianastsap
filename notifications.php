<?php
require_once "../includes/auth.php"; require_login();
$conn->query("UPDATE notifications SET is_read=1 WHERE user_id=".(int)$_SESSION['user']['id']);
$notes=$conn->query("SELECT * FROM notifications WHERE user_id=".(int)$_SESSION['user']['id']." ORDER BY id DESC LIMIT 100");
include "../includes/header.php";
?>
<div class="hero"><h1>Notifikasi Saya</h1><p>Notifikasi pendaftaran dan aktivitas akun.</p></div>
<div class="table-card"><?php if($notes->num_rows===0): ?><div class="empty">Belum ada notifikasi.</div><?php else: while($n=$notes->fetch_assoc()): ?><div class="notice"><b><?=e($n['title'])?></b><br><?=e($n['message'])?><div class="muted"><?=e($n['created_at'])?></div></div><?php endwhile; endif; ?></div>
<?php include "../includes/footer.php"; ?>
