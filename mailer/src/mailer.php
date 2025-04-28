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

function sendMail($host = 'smtp.gmail.com', $defaultMail, $mailPass, $ownerName, $toName, $toMail, $subject, $body, $altBody) {
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

// KAYNAK:
// - https://www.w3schools.com/php/php_mysql_select.asp
?>
