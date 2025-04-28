<?php
// require_once("func.inc");
function log_auth_event($message) {
    $logfile = '/var/log/system.log';

    // Dosya yoksa oluştur
    if (!file_exists($logfile)) {
        file_put_contents($logfile, "");
    }

    // Dosya yazılabilir mi diye kontrol et
    if (is_writable($logfile)) {
        $date = date('M d H:i:s'); // Örn: Apr 28 15:11:38
        $process = "php-fpm[" . getmypid() . "]"; // php-fpm[PID]

        $line = "$date RaporAnaliz $process: /admin_login.php: $message" . PHP_EOL;
        file_put_contents($logfile, $line, FILE_APPEND);
    } else {
        error_log("Log dosyasına yazılamadı: $logfile");
    }
}
// Include config file
session_start();
require_once "../config.php";
ini_set('display_errors', 1);
$referer = $_SERVER['HTTP_REFERER'];

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Oturum açılmamışsa veya oturum doğrulanmamışsa, admin.php'ye erişim izni verme
    header("location: ../admin_login.php");
    exit;
}
// Kullanıcı admin.php sayfasından geliyorsa veya oturum açılmışsa giriş izni ver
// if (strpos($referer, 'admin.php') !== false || strpos($referer, 'kullaniciyi_ekle.php') !== false || (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
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

 
$password="";
$username="";


if($_SERVER["REQUEST_METHOD"] == "POST"){

 // Validate username
     // Check if password is empty
     if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

 if(empty(trim($_POST["username"]))){
     $username_err = "Please enter a username.";
 } elseif(!preg_match('/^[0-9]{11}$/', trim($_POST["username"]))){
     $username_err = "Telefon numarasi sadece 11 rakamdan olusmali.";
 } else{
     // Prepare a select statement
     $sql = "SELECT id FROM radcheck WHERE username = :username";
     
     if($stmt = $pdo->prepare($sql)){
         // Bind variables to the prepared statement as parameters
         $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
         
         // Set parameters
         $param_username = trim($_POST["username"]);
         
         // Attempt to execute the prepared statement
         if($stmt->execute()){
             if($stmt->rowCount() == 1){
                 $username_err = "This username is already taken.";
             } else{
                 $username = trim($_POST["username"]);
                     $sql = "INSERT INTO radcheck (username, attribute, op, value) VALUES (:username, 'Cleartext-Password', ':=', :value)";
                      
                     if($stmt = $pdo->prepare($sql)){
                         // Bind variables to the prepared statement as parameters
                         $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                         $stmt->bindParam(":value", $param_password, PDO::PARAM_STR);
                         
                         // Set parameters
                         $param_username = $username;
                         $param_password = $password; 
                         //password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                         // Attempt to execute the prepared statement
                         if($stmt->execute()){
                         echo "<br>Yeni kullanici eklendi.";
                         echo "<br>";
                         $success_message = " Yeni kullanici eklendi.";
                         
                         $ip = $_SERVER['REMOTE_ADDR'];                       

                         log_auth_event("New user added '$username' from: $ip (Local Database)");
        
                        // index.php yonlendir orda istegi al
                        header("location: kullanici_ekle.php?success_message=" . urlencode($success_message));
    
             
                             // header("location: $sms_url");
                         } else{
                             echo "Oops! Something went wrong. Please try again later.";
                         }
             
                         // Close statement
                         unset($stmt);
                     }
             
             }
         } else{
             echo "Oops! Something went wrong. Please try again later.";
         }
         // Close statement
         unset($stmt);
     }
 }
 
 // Close connection
 unset($pdo);
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
 </style>
</head>
<body>
 <div class="wrapper">
     <h2>Manuel Kayit</h2>
     <p>Olusturmak istediginiz kulllanici bilgilerini giriniz</p>
     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
         <div class="form-group">
             <label>gsm numarasi</label>
             <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
             <span class="invalid-feedback"><?php echo $username_err; ?></span>
         </div>
         <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>

         <div class="form-group">
             <input type="submit" class="btn btn-primary" value="Submit">
         </div>
         <p>Butun kullanicilari gormek  <a href="list.php">icin tikla</a>.</p>
     </form>
 </div>    
</body>
</html>