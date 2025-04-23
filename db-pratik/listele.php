<?php
//new ile PDO adli array olusturulur bu arrayy icine user ,pass  mysql:host=localhost dbname istege bagli charset girilir
//farkli veri tabanlarinda farkli soz dizimi olabilir dikkat

// 
// $db = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4','username', 'password');
// 

//pdo calismadiginda hata alinabilir bu yuzden hersey try / catch blogu altinda olmali

try {

    $db = new PDO($dsn, $user, $password);
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );   // hata mesajini alabilmek icin ayar yaptik

} catch (PDOException $e) {

    echo 'Connection failed: ' . $e->getMessage();
}

?>
