<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

$register = [];
$gateway_alarms = [];
$successful_logins = [];

// register tablosunu çek (önceden attacks idi)
$query = $db->query("SELECT log_date, log_message FROM register ORDER BY log_date ASC");
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $register[] = $row;
}

// gateway_alarms tablosunu çek
$query = $db->query("SELECT log_date, log_message FROM auth_errors ORDER BY log_date ASC");
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $gateway_alarms[] = $row;
}

// successful_logins tablosunu çek
$query = $db->query("SELECT log_date, log_message FROM successful_logins ORDER BY log_date ASC");
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $successful_logins[] = $row;
}

// JSON olarak çıktıyı ver
echo json_encode([
    'register' => $register,
    'gateway_alarms' => $gateway_alarms,
    'successful_logins' => $successful_logins
]);
?>