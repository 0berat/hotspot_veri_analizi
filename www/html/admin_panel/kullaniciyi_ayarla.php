<?php
// Veritabanı bağlantısını config.php dosyasından sağla
require_once '../config.php';
// ini_set('display_errors', 1);
$referer = $_SERVER['HTTP_REFERER'];
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Oturum açılmamışsa veya oturum doğrulanmamışsa, admin.php'ye erişim izni verme
    header("location: ../admin_login.php");
    exit;
}

// Kullanıcı admin.php sayfasından geliyorsa veya oturum açılmışsa giriş izni ver
// if (strpos($referer, 'admin.php') !== false || strpos($referer, 'kullaniciyi_ayarla.php') !== false || (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
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


$newUsername = "";
$password = "";
$id = "";


if(empty(trim($_POST["id"]))){
 $id_err = "Please enter a id.";
}
else{
    if(empty(trim($_POST["newUsername"]))){
        $newUsername_err = "Please enter a new username.";
       } elseif(!preg_match('/^[0-9]{11}$/', trim($_POST["newUsername"]))){
        $newUsername_err = "Telefon numarasi sadece 11 rakamdan olusmali.";
       } else{
        
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter your password.";
        } else{
            $id = trim($_POST["id"]);
            $newUsername = trim($_POST["newUsername"]);        
            $password = trim($_POST["password"]);
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
   
                            $stmt = $pdo->prepare("UPDATE radcheck SET username = :newUsername, value = :newValue WHERE id = :id");
                            //tanimliyoruz.
                            $stmt->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
                            $stmt->bindParam(':newValue', $password, PDO::PARAM_STR);
                            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
                            
                            
                            $stmt->execute();
                            $success_message = " Kullanici bilgileri guncellendi.";
                    
                            // index.php yonlendir orda istegi al
                            header("location: kullaniciyi_ayarla.php?success_message=" . urlencode($success_message));
            
                        } catch(PDOException $e) {
                            echo "Hata: " . $e->getMessage();
                        }                      
}else{
    echo "bu id yok";
    $fail_message = " $id id numarali bir kayit yok.";
        
    // index.php yonlendir orda istegi al
    header("location: kullaniciyi_ayarla.php?fail_message=" . urlencode($fail_message));

}
                }
            }           
            
            
        
        
        
        
        }
    }
}





?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
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
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
             <label>Degistirmek istediginiz numaranin idsini giriniz</label>
             <input type="text" name="id" class="form-control <?php echo (!empty($id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $id; ?>">
             <span class="invalid-feedback"><?php echo $id_err; ?></span>
         </div>
         <div class="form-group">
             <label>yeni gsm numarasi</label>
             <input type="text" name="newUsername" class="form-control <?php echo (!empty($newUsername_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $newUsername; ?>">
             <span class="invalid-feedback"><?php echo $newUsername_err; ?></span>
         </div>

         <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Butun kullanicilari gormek  <a href="list.php">icin tikla</a>.</p>
        </form>
    </div>    
</body>
</html>
<?php require_once "list.php" ;
?>