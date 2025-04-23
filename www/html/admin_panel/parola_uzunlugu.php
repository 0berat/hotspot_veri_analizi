<html>
    <head>
    <meta charset="UTF-8">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    body{ font: 14px sans-serif; }
     .wrapper{ width: 200; padding: 20px; }
</style>
    </head>

<body>
<div class="wrapper">

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <h5 style="width: 500;">Parola uzunlugunu giriniz: </h5>
    <div class="form-group">
    <input type="number" name="length" required class="form-control" min="1"><br>
    </div>
    <div class="form-group">
    <span class="invalid-feedback"></span>
    </div>
    <div class="form-group">
    <input type="submit" class="btn btn-primary" value="Uygula">
    </div>
    <p>admin.php'ye git <a href="../admin.php">Buradan gir</a>.</p>

</form>
</div>
</body>

</html>

<?php 
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Oturum açılmamışsa veya oturum doğrulanmamışsa, admin.php'ye erişim izni verme
    header("location: ../admin_login.php");
    exit;
}

$length = trim($_POST["length"]);

if($length===""){
// echo'deger giriniz';
$oku = fopen("varsayilan_deger.txt",'r');
$icerik = fread($oku, filesize('varsayilan_deger.txt'));
echo "<h5>Suanki parola uzunlugu:$icerik</h5> ";
fclose($oku);

}else{$ac = fopen("varsayilan_deger.txt",'w');
    fwrite($ac,$length);
    fclose($ac);
    $oku = fopen("varsayilan_deger.txt",'r');
    $icerik = fread($oku, filesize('varsayilan_deger.txt'));
    echo "<h5>Suanki parola uzunlugu:$icerik</h5> ";
    fclose($oku);
    }



?>