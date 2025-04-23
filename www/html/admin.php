<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Oturum açılmamışsa veya oturum doğrulanmamışsa, admin.php'ye erişim izni verme
    header("location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {font-family: "Lato", sans-serif;}

.sidebar {
  height: 100%;
  width: 180px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  padding-top: 16px;
}

.sidebar a {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 20px;
  color: #818181;
  display: block;
}

.sidebar a:hover {
  color: #f1f1f1;
}

.main {
  margin-left: 180px; /* Same as the width of the sidenav */
  padding: 0px 10px;
}

@media screen and (max-height: 450px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
}
</style>
</head>
<body>

<div class="sidebar">
  <a href="#"><i class=""></i> Kullanicilari;</a>
  <a href="admin_panel/list.php"><i class="fa fa-list"></i> Listele</a>
  <a href="admin_panel/kullaniciyi_ayarla.php"><i class="fa fa-exchange"></i>  Ayarla</a>
  <a href="admin_panel/kullaniciyi_sil.php"><i class="fa fa-minus"></i> Sil</a>
  <a href="admin_panel/kullanici_ekle.php"><i class="fa fa-plus"></i> Ekle</a>
  <hr>
  <a href="admin_panel/parola_uzunlugu.php"><i class="fa fa-cog"></i> Parola Uzunlugunu Belirle</a>
  <hr>
  <a href="admin_panel/zaman_kontrol.php"><i class="fa fa-cog"></i> Kullanici Suresi</a>
</div>

<div class="main">
  <h2>Kullanici Yonetim sistemine Hosgeldiniz</h2>
  <p>Sol taraftaki side bar ile kullaniclarin ustunde degisikler yapabilirsiniz.</p>
  <p>Kaydolmus kullancinin bilgilerini degistirebilir,silebilir ayni zamanda yeni bir kullanici olusturabilirsiniz.</p>
  <p>Ve son olarak kullanici register olduktan sonra giden parolanin uzunlugunu burada kalici olarak degistirebilirsiniz.</p>
  <p></p>
</div>
     
</body>
</html> 
