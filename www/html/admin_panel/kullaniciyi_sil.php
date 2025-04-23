<?php
// Veritabanı bağlantısını config.php dosyasından sağla

include '../config.php';
// Form gönderildi mi kontrol et
$referer = $_SERVER['HTTP_REFERER'];

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Oturum açılmamışsa veya oturum doğrulanmamışsa, admin.php'ye erişim izni verme
    header("location: ../admin_login.php");
    exit;
}

// Kullanıcı admin.php sayfasından geliyorsa veya oturum açılmışsa giriş izni ver
// if (strpos($referer, 'admin.php') !== false || strpos($referer, 'kullaniciyi_sil.php') !== false || (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
//     // Kullanıcı giriş izni verildi, işlemlere devam et
// } else {
//     // Kullanıcı admin.php sayfasından gelmiyorsa ve oturum açılmamışsa giriş izni yok
//     header("location: ../admin_login.php");
//     exit;
// }

if(isset($_GET["success_message"])){
    $success_message = $_GET["success_message"];
    echo '<div class="success-message">' . htmlspecialchars($success_message) . '</div>';
}

if(isset($_GET["fail_message"])){
    $fail_message = $_GET["fail_message"];
    echo '<div class="fail-message">' . htmlspecialchars($fail_message) . '</div>';
}


$id = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kullanıcıdan gelen ID değerini al
    if(empty(trim($_POST["id"]))){
        $id_err = "ID numarisi dogru giriniz.";
    } else{

        $id = trim($_POST["id"]);
        /////////////////////////////
        $sql = "SELECT id FROM radcheck WHERE id = :id";
        
if($stmt = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":id", $param_id, PDO::PARAM_STR);
    
    // Set parameters
    $param_id = $id;
    
    // Attempt to execute the prepared statement
    if($stmt->execute()){
        if($stmt->rowCount() == 1){
            
        try {
                // Silme sorgusunu hazırla
                $stmt = $pdo->prepare("DELETE FROM radcheck WHERE id = :id");
                
                // Parametreyi bind et
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                
                // Sorguyu çalıştır
                $stmt->execute();
        
                echo "<h4>ID $id numaralı kayıt başarıyla silindi.</h4>";
                $success_message = " $id id numaralı kayıt başarıyla silindi.";
        
                // index.php yonlendir orda istegi al
                header("location: kullaniciyi_sil.php?success_message=" . urlencode($success_message));
    
            } catch(PDOException $e) {
                // Hata durumunda hata mesajını göster
                echo "Hata: " . $e->getMessage();
            }
        } else{
            $fail_message = " $id id numarali bir kayit yok.";
        
            // index.php yonlendir orda istegi al
            header("location: kullaniciyi_sil.php?fail_message=" . urlencode($fail_message));
        }
    }
}

        ////////////////////
        
    }

    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

 <style>
     body{ font: 14px sans-serif; }
     .wrapper{ width: 200; padding: 20px; }
     .success-message {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        text-align: center;
        font-size: 18px;
    }
    .fail-message {
        background-color: #E32800;
        color: white;
        padding: 10px;
        text-align: center;
        font-size: 18px;
    }

 </style>
 </head>
 <body>

 <div class="wrapper">

<!-- Kullanıcıdan ID değerini girmesi için bir form oluştur -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <div class="form-group">
        <label for="" style="font-size: 20px;width: 700;"> Silmek istediginiz kullanicinin ID Numarasi giriniz:</label>
    <input type="number" name="id" required class="form-control <?php echo (!empty($id_err)) ? 'is-invalid' : ''; ?>"><br>
    </div>
    <span class="invalid-feedback"><?php echo $id_err; ?></span>
    <div class="form-group">
    <input type="submit" class="btn btn-primary" value="Sil"style="margin-top: -25px;">
    </div>
</form>
</div>
</body>
</html>
<?php require_once 'list.php'; ?>
