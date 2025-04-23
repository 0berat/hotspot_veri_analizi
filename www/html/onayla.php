<?php
ini_set('display_errors', 1);
include 'config.php';
$username=urldecode($_GET['username']);//gsm he 
$param_username=urldecode($_GET['username']);
if($_SERVER["REQUEST_METHOD"] == "GET"){
$username_err='';
        

                    // $username = trim($_POST["username"]);

                    // Parametre parolanin karakter uzunlugudur.
                    $password = generateRandomPassword(5);
                    $sms_message = "Kaydiniz Admin Tarafindan onaylandi.Wifi'ye hos geldiniz. Kullanci isminiz: $username Parolan: $password %0A";
                    // $sms_message = "kullanci_ismin:_$username%20parolan:$password%0A";
                    $sms_username ="-";
                    // $sms_username ="fatih1s";
                    $sms_password ="-";
                    $sms_url = "-" . $sms_username . "&password=" . $sms_password . "&gsm=" . $username . "&smsmetni=" . urlencode($sms_message);
                    // Check input errors before inserting in database
                        echo  urlencode($sms_message);
                
                        // Prepare an insert statement
                        $sql = "INSERT INTO radcheck (username, attribute, op, value) VALUES (:username, 'Cleartext-Password', ':=', :value)";
                         
                        if($stmt = $pdo->prepare($sql)){
                            // Bind variables to the prepared statement as parameters
                            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                            $stmt->bindParam(":value", $param_password, PDO::PARAM_STR);
                            
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
                    
 
    
    // Close connection
    unset($pdo);
}
function generateRandomPassword($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password2 = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $password2 .= $characters[$randomIndex];
    }
    
    return $password2;
}

?>