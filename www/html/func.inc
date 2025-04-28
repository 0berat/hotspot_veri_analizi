<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function load_config() {
    global $config;

    $config_file = './config.json';

    if (file_exists($config_file)) {
        $json_data = file_get_contents($config_file);
        $config = json_decode($json_data, true);
        
        // JSON geçerli değilse, boş array başlat
        if ($config === null) {
            $config = [];
            error_log("Geçersiz JSON verisi. Boş config başlatıldı.");
        }

        // Burada tüm config dizisini döndürüyoruz
        return $config;
    } else {
        // Dosya yoksa, boş array başlat
        $config = [];
        return [];
    }
}
function write_config($description = '') {
    global $config;

    $filename = './config.json'; // Kaydedilecek dosya yolu

    // JSON verisini encode et
    // var_dump($config);die();
    $json_data = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // Dosyaya yazma işlemi
    if (file_put_contents($filename, $json_data) === false) {
        die("Config dosyası yazılamadı!");  // Hata mesajı, burada die yerine exception kullanmak daha iyi olabilir
    }

    // Eğer bir açıklama varsa loglayalım
    if (!empty($description)) {
        error_log("Config güncellendi: " . $description);
    }
}



function report_connect_db(){
    $db = new SQLite3('/var/www/html/rapor_db/analiz_rapor.db');
    // $db->exec('CREATE TABLE IF NOT EXISTS gateway_alarms (id INTEGER PRIMARY KEY AUTOINCREMENT, log_date TEXT, log_message TEXT)');
    // $db->exec('CREATE TABLE IF NOT EXISTS attacks (id INTEGER PRIMARY KEY AUTOINCREMENT, log_date TEXT, log_message TEXT)');
    $db->exec('CREATE TABLE IF NOT EXISTS successful_logins (id INTEGER PRIMARY KEY AUTOINCREMENT, log_date TEXT, log_message TEXT)');
    $db->exec('CREATE TABLE IF NOT EXISTS auth_errors (id INTEGER PRIMARY KEY AUTOINCREMENT, log_date TEXT, log_message TEXT)');
    $db->exec('CREATE TABLE IF NOT EXISTS register (id INTEGER PRIMARY KEY AUTOINCREMENT, log_date TEXT, log_message TEXT)');
    // $db->exec('CREATE TABLE IF NOT EXISTS openvpn_auth (id INTEGER PRIMARY KEY AUTOINCREMENT, log_date TEXT, log_message TEXT)');
    return $db;
}
function log_auth_event($message) {
    $logfile = '/var/log/system.log';

    // Dosya yoksa oluştur
    if (!file_exists($logfile)) {
        file_put_contents($logfile, "");
    }

    // Dosya yazılabilir mi diye kontrol et
    if (is_writable($logfile)) {
        $date = date('M d H:i:s'); // Örn: Apr 28 15:11:38
        $process = "php-fpm[" . getmypid() . "]"; // php-fpm[PID]

        $line = "$date RaporAnaliz $process: /admin_login.php: $message" . PHP_EOL;
        file_put_contents($logfile, $line, FILE_APPEND);
    } else {
        error_log("Log dosyasına yazılamadı: $logfile");
    }
}

?>