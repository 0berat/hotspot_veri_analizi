<?php
require "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);


        
    // Attempt to execute the prepared statement
            
        try {
                // Silme sorgusunu hazırla
                $zaman = 'admin_panel/zaman_deger.txt';
                $silmeSuresi = file_get_contents($zaman);
                echo $silmeSuresi;

                 // 30 gün (saniye cinsinden)
                $silmeZamani = time() - $silmeSuresi;
                
                $sql = "DELETE FROM radcheck WHERE UNIX_TIMESTAMP(kayit_tarihi) < :silmeZamani";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':silmeZamani', $silmeZamani, PDO::PARAM_INT);
            
                $stmt->execute();
                
            } catch(PDOException $e) {
                // Hata durumunda hata mesajını göster
                echo "Hata oluştu: " . $e->getMessage();

            }
       


?>