<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
<style>
    .success-message {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        text-align: center;
        font-size: 18px;
    }
</style>

    <title>Giriş Sayfası</title>
</head>
<body>
<?php
session_start();
$username = "";
$password = "";
    if(isset($_GET["success_message"])){
        $success_message = $_GET["success_message"];
        echo '<div class="success-message">' . htmlspecialchars($success_message) . '</div>';
    }
    // Form gönderildiğinde işlenecek kod
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Kullanıcı adı ve parolayı alın
        $username = $_POST["username"];
        $password = $_POST["password"];
        // $base_grant_url = urldecode($_GET['base_grant_url']);

    }
    ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="wrapper"> 

    <div class="form-group">
        <label for="username">GSM no'nuzu giriniz</label>
        <input type="text" name="username" class="form-control" required><br><br>
    </div>
    <div class="form-group">
        <label for="password">Parola:</label>
        <input type="password" name="password" class="form-control" required><br><br>
    </div>
    <div class="form-group">
    <input type="submit" value="Giriş Yap" class="btn btn-primary">
        <p>Hesabin yok mu? <a href="sec_register.php">buraya tikla</a>.</p>
    </div>
    </div>
    </form>
</body>
</html>



<?php
// ini_set('display_errors', 1);
// ip ve mac adresleri
$node_mac = urldecode($_GET['node_mac']);
$client_mac = urldecode($_GET['client_mac']);
$client_ip = urldecode($_GET['client_ip']);
$_SESSION['node_mac'] = $node_mac;
$_SESSION['client_mac'] = $client_mac;
$_SESSION['client_ip'] = $client_ip;

echo "<br> node mac:" . $_SESSION['node_mac'];
echo "<br> client ip:" . $client_ip;
echo "<br> client mac:" . $client_mac."<br>";


//base url ve contuine url
$base_grant_url = urldecode($_GET['base_grant_url']);
$user_continue_url = urldecode($_GET['user_continue_url']);
// echo "<br> base grant:" . $base_grant_url;
// echo "<br> user contiune url:" . $user_continue_url;

$redirect_url = $_GET['base_grant_url'] . "?continue_url=" . $_GET['user_continue_url'];  // Normalde bize bu sekilde geri donus yap diyor.
// $redirect_url= urlencode($base_grant_url) . "?continue_url=" . urlencode($user_continue_url); //encode hali 

// echo "<br> redirect url:" . $redirect_url;
// header('Location: ' . $redirect_url);


/**
 * RADIUS client example using PAP password.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/Radius/autoload.php';
// echo "redirect url: ". $redirect_url;

//~ $server = (getenv('RADIUS_SERVER_ADDR')) ?: '127.0.0.1';
$server = (getenv('RADIUS_SERVER_ADDR')) ?: '127.0.0.1';
$user   = $username;
$pass   = $password;
$secret = (getenv('RADIUS_SECRET'))      ?: 'testing123';

 $radius = new \Dapphp\Radius\Radius();
 $radius->setServer($server)        // IP or hostname of RADIUS server
        ->setSecret($secret);   // RADIUS shared secret


// // // Send access request for a user with username = 'username' and password = 'password!'
// // echo "Sending access request to $server with username $user\n<br>";
 $response = $radius->accessRequest($user, $pass);
 var_dump($response);
if ($response === false) {


   echo "<br>yanlis kullanici adi veya parola<br>";
   echo "kullanici isminiz: $user  <br>";
   echo "parolaniz: $pass <br>";

}
 else {

    // var_dump("ddddddddddd");die();
//Kullanici girisi dogru yapildiginda yapilacak islevler buraya yaziliyor
// $ipAdresi = $_SERVER['REMOTE_ADDR'];
// header('Location: http://localhost:5001/splash/grant?continue_url=https://developer.cisco.com/meraki');
header('Location: https://www.google.com/'); //dogru giris yapildiginda google'a yonlendiriyor
// header('Location: ' . $redirect_url);  // DEGISKEN BOS OLUNCA SIKINTI YARATIYOR. GET ILE GONDERMEYI DENE.
// exit;
// echo " <br>IP Adresiniz: $ipAdresi <br> " ;
echo "<br>Success! Basarili Giris  Received Access-Accept response from RADIUS server.<br> ";
}
#$radius = radius_auth_open();
# phpinfo(); 
?>
<!-- <script>
window.addEventListener('mouseover', initLandbot, { once: true });
window.addEventListener('touchstart', initLandbot, { once: true });
var myLandbot;
function initLandbot() {
  if (!myLandbot) {
    var s = document.createElement('script');s.type = 'text/javascript';s.async = true;
    s.addEventListener('load', function() {
      var myLandbot = new Landbot.Livechat({
        configUrl: 'https://storage.googleapis.com/landbot.online/v3/H-1765928-HQ2PEXD9KOE0TNOY/index.json',
      });
    });
    s.src = 'https://cdn.landbot.io/landbot-3/landbot-3.0.0.js';
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  }
}
</script> -->