<?php
// Include config file
require_once "config.php";
ini_set('display_errors', 1);

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
// $attribute = 'Cleartext-Password';
// $op = ':=';
//fonksiyonlar
$varsayilan = './admin_panel/varsayilan_deger.txt';
$length = file_get_contents($varsayilan);

function generateRandomPassword($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password2 = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $password2 .= $characters[$randomIndex];
    }
    
    return $password2;
}
//Get istegi gondermek icin bu fonksiyonu cagiriyoruz.
function sendGetRequest($url) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}
function tcKimlikDogrula($tcno, $isim, $soyisim, $dogumtarihi) {
    $client = new SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL");
    try {
        $sonuc = $client->TCKimlikNoDogrula([
            'TCKimlikNo' => $tcno,
            'Ad' => $isim,
            'Soyad' => $soyisim,
            'DogumYili' => $dogumtarihi
        ]);

        if ($sonuc->TCKimlikNoDogrulaResult) {
            // T.C. Kimlik No doğruysa yapılacak işlemler buraya gelecek
            // Örneğin, kullanıcıyı sisteme giriş yapmaya yönlendirme, vs.
            return true;
        } else {
            // T.C. Kimlik No yanlışsa yapılacak işlemler buraya gelecek
            // Örneğin, hatalı giriş uyarısı gösterme, vs.
            return false;
        }
    } catch (Exception $e) {
        $faultstring='';
        // SOAP isteğinde hata oluştuğunda yapılacak işlemler buraya gelecek
        // Örneğin, hata mesajını kaydetme veya kullanıcıya gösterme, vs.
        echo $e->$faultstring;
        return false;
    }
}





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
                    $tcno = $isim = $soyisim = $dogumtarihi="";
                    $tcno = trim($_POST["tcno"]);
                    $isim = trim($_POST["isim"]);
                    $soyisim = trim($_POST["soyisim"]);
                    $dogumtarihi = trim($_POST["dogumtarihi"]);
                    // tc kimlik dogrulamasi sonraki parantezde db islemleri
                    if (tcKimlikDogrula($tcno, $isim, $soyisim, $dogumtarihi)) {
                        echo 'T.C. Kimlik No Doğru';
                        // Tc dogrulandi diger islemleri yap
                        $password = generateRandomPassword($length);

                    $username = trim($_POST["username"]);    

                    
                    // Parametre parolanin karakter uzunlugudur.
                    $sms_message = "Wifi'ye hos geldiniz. Kullanci isminiz: $username Parolan: $password %0A";
                    // $sms_message = "kullanci_ismin:_$username%20parolan:$password%0A";
                    $sms_username ="-";
                    $sms_password ="-";
                    $sms_url = "-" . $sms_username . "&password=" . $sms_password . "&gsm=" . $username . "&smsmetni=" . urlencode($sms_message);
                    // Check input errors before inserting in database
                        echo  urlencode($sms_message);
                
                        // Prepare an insert statement
                        $sql = "INSERT INTO radcheck (username, attribute, op, value, tc_kimlik) VALUES (:username, 'Cleartext-Password', ':=', :value, :tc_kimlik)";
                         
                        if($stmt = $pdo->prepare($sql)){
                            // Bind variables to the prepared statement as parameters
                            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                            $stmt->bindParam(":value", $param_password, PDO::PARAM_STR);
                            $stmt->bindParam(":tc_kimlik", $tcno, PDO::PARAM_STR);
                            
                            // Set parameters
                            $param_username = $username;
                            $param_password = $password; 
                            //password_hash($password, PASSWORD_DEFAULT); // parolayi hashliyoruz
                            // Attempt to execute the prepared statement
                            if($stmt->execute()){
                                // Islem basarili ise yapilicaklar
                                // echo $sms_url;
                            //get fonksiyonunu cagir.
                            // $response = sendGetRequest($sms_url);
                            // echo $response ;

                            $success_message = "Başarıyla kaydınız oluşturuldu.";
    
                            // index.php yonlendir orda istegi al
                            header("location: index.php?success_message=" . urlencode($success_message));
                            echo "<br>ISTEK ATILDI ISTEK ATILDI";
                            echo "<br>";
                            echo $sms_url;
                            echo "<br>";
                
                                // header("location: $sms_url");
                            } else{
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                
                            // Close statement
                            unset($stmt);
                        }
                    } else {
                        // T.C. Kimlik No yanlışsa veya hata varsa yapılacak işlemler
                        echo 'T.C. Kimlik No Hatalı';
                        echo $tcno,$isim,$soyisim,$dogumtarihi;

                        // Diğer işlemleri yapabilirsiniz
                    }
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            unset($stmt);
        }
    }
    
    // Validate password
    
    // Validate confirm password
    
    
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
                <label>TC</label>
                <input type="text" name="tcno" class="form-control ">
                <span class="invalid-feedback"></span>
            </div>    
            <div class="form-group">
                <label>isim</label>
                <input type="text" name="isim" class="form-control"onkeyup="this.value = this.value.toUpperCase();">
                <span class="invalid-feedback"></span>
            </div>    
            <div class="form-group">
                <label>soyisim</label>
                <input type="text" name="soyisim" class="form-control"onkeyup="this.value = this.value.toUpperCase();">
                <span class="invalid-feedback"></span>
            </div>    
            <div class="form-group">
                <label>dogum tarihi</label>
                <input type="text" name="dogumtarihi" class="form-control  ">
                <span class="invalid-feedback"></span>
            </div>    


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