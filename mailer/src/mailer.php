<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer autoload

/*
	$toName: bu parametre gönderilecek kullanıcının adı.
	$toMail: bu parametre gönderilecek kullanıcının mail hesabıdır (gmail tercihimizdir.)
	$subject: bu parametre gönderilecek içeriğin başlığı.
	$body: bu parametre gönderilecek içerik.
	$altBody: bu parametre gönderilecek içeriğin devamıdır.
	$defaultMail: bu parametre gönderen kişinin (bizim) mailimiz.
	$mailPass: bu parametre bizim mailimizin parolasıdır.
	$ownerName: bu bizim adımız.
	$host: buna da elleşmeye gerek yok, gmail kullanıyoz.
*/

function sendMail($toName,  $toMail, $subject, $body, $altBody, $host = 'smtp.gmail.com') {
	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();

	$DB_USER = getenv("DB_USER");
	$DB_NAME = getenv("DB_NAME");
	$DB_PASS = getenv("DB_PASS");
	$DB_HOST = getenv("DB_HOST");
	$defaultMail = getenv("MAIL");
	$mailPass = getenv("MAIL_PASS");
	$ownerName = getenv("NAME");

	$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
	// Bağlantı kontrol
	if ($conn->connect_error) {
	  die("Veri tabanına bağlanamadı: " . $conn->connect_error);
	}

	$mail = new PHPMailer(true);
	try {
	    // Sunucu ayarları
	    $mail->isSMTP();
	    $mail->Host       = $host;
	    $mail->SMTPAuth   = true;
	    $mail->Username   = $defaultMail;				// Gmail adresin
	    $mail->Password   = $mailPass;       			// Gmail uygulama şifresi
	    $mail->SMTPSecure = 'tls';                   	// 'tls' ya da 'ssl'
	    $mail->Port       = 587;                    	// TLS için 587, SSL için 465
	    $mail->setFrom($defaultMail, $ownerName);

	    // Alıcılar
	    $mail->addAddress($toMail, $toName);

	    // İçerik
	    $mail->isHTML(true);
	    $mail->Subject = $subject;
	    $mail->Body    = $body;
	    $mail->AltBody = $altBody;

	    $mail->send();
	    echo 'Mail başarıyla gönderildi';
	} catch (Exception $e) {
	    echo "Mail gönderilemedi. Hata: {$mail->ErrorInfo}";
	}
}

sendMail();
?>

// KAYNAK:
// - https://www.w3schools.com/php/php_mysql_select.asp
