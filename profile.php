<?php
require_once "../includes/auth.php"; require_login(); if($_SESSION['user']['role']!=='user'){header("Location: ../admin/dashboard.php");exit;}
$id=(int)$_SESSION['user']['id'];$error='';
$stmt=$conn->prepare("SELECT * FROM users WHERE id=?");$stmt->bind_param("i",$id);$stmt->execute();$u=$stmt->get_result()->fetch_assoc();$stmt->close();
if($_SERVER['REQUEST_METHOD']==='POST'){
 $name=trim($_POST['name']);$nisn=trim($_POST['nisn']);$ttl=trim($_POST['ttl']);$gender=$_POST['gender'];$email=trim($_POST['email']);$phone=trim($_POST['phone']);$address=trim($_POST['address']);
 if(!preg_match('/^\d{10}$/',$nisn)) $error='NISN harus 10 digit.';
 else {$stmt=$conn->prepare("UPDATE users SET name=?,nisn=?,ttl=?,gender=?,email=?,phone=?,address=? WHERE id=?");$stmt->bind_param("sssssssi",$name,$nisn,$ttl,$gender,$email,$phone,$address,$id);if($stmt->execute()){$_SESSION['user']['name']=$name;notify_all_admins($conn,'User memperbarui data',$name.' memperbarui profilnya.','info');header("Location: profile.php?ok=1");exit;}else $error='Gagal memperbarui profil.';}
}
include "../includes/header.php";
?>
<div class="hero"><h1>Profil Saya</h1></div>
<?php if($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?><?php if(isset($_GET['ok'])): ?><div class="alert alert-success">Profil berhasil diperbarui.</div><?php endif; ?>
<div class="form-card"><form method="post"><div class="form-grid">
<div class="field"><label>Nama</label><input name="name" value="<?=e($u['name'])?>" required></div><div class="field"><label>NISN</label><input name="nisn" maxlength="10" value="<?=e($u['nisn'])?>" required></div>
<div class="field"><label>TTL</label><input name="ttl" value="<?=e($u['ttl'])?>" required></div><div class="field"><label>Gender</label><select name="gender"><option value="male" <?=$u['gender']==='male'?'selected':''?>>Male</option><option value="female" <?=$u['gender']==='female'?'selected':''?>>Female</option></select></div>
<div class="field"><label>Email</label><input type="email" name="email" value="<?=e($u['email'])?>" required></div><div class="field"><label>Nomor Telepon</label><input type="tel" name="phone" value="<?=e($u['phone'])?>" required></div>
<div class="field full"><label>Alamat</label><textarea name="address" required><?=e($u['address'])?></textarea></div></div><br><button class="btn btn-primary">Update Profil</button></form></div>
<?php include "../includes/footer.php"; ?>
