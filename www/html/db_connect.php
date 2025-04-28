<?php
try {
    $db = new PDO('sqlite:/var/www/html/rapor_db/analiz_rapor.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Veritabanına bağlanılamadı: ' . $e->getMessage());
}
?>
