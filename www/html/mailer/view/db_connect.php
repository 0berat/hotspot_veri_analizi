<?php
try {
    $db = new PDO("sqlite:/var/sqlite/mail.db");
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>
