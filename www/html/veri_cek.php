<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

$attacks = [];
$gateway_alarms = [];
$successful_logins = [];

// attacks tablosunu çek
$query = $db->query("SELECT log_date, log_message FROM attacks ORDER BY log_date ASC");
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $attacks[] = $row;
}

// gateway_alarms tablosunu çek
$query = $db->query("SELECT log_date, log_message FROM gateway_alarms ORDER BY log_date ASC");
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
    'attacks' => $attacks,
    'gateway_alarms' => $gateway_alarms,
    'successful_logins' => $successful_logins
]);
?>
