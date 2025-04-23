<?php
// Include config file
require_once "config.php";
// ini_set('display_errors', 1);

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[0-9]{11}$/', trim($_POST["username"]))){
        $username_err = "Telefon numaraniz sadece 11 rakamdan olusmali.";
    } else{
        // Ayni kulllanaci var mi kontrol ediyoruz
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

                    // tc kimlik dogrulamasi sonraki parantezde db islemleri

                    $username = trim($_POST["username"]);
                    // MAIL GONDERME 
                    include 'php-mailsender/mailsend.php';
                    $success_message = "Talebiniz alinmistir. Sistem yonetici onayini bekleyiniz.";

                    // index.php yonlendir orda istegi al
                    header("location: index.php?success_message=" . urlencode($success_message));
                    echo "<br>ISTEK ATILDI ISTEK ATILDI";
                    echo "<br>";
                    echo "<br>";

                    // Parametre parolanin karakter uzunlugudur.

                        // Prepare an insert statement

                     
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
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <div class="form-group">
                <label>gsm numarasi</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Hesabin zaten var mi? <a href="index.php">Buradan gir</a>.</p>
        </form>
    </div>    
</body>
</html>
<?php

?>