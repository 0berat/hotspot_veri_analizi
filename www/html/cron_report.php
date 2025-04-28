<?php
require_once("func.inc");
$config = load_config();
// var_dump($config);die();

$report = &$config['report'];
if( $report['report'] == 'off' ){
    echo "Report is off";
    exit();
}
// require_once("config.inc");
// require_once("coslat.inc");
// require_once("widget.inc");

global $config;
function monthToNumber($month) {
    $months = [
        'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06',
        'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
    ];
    return isset($months[$month]) ? $months[$month] : null;
}

$db = report_connect_db();

$commands = [
    // 'grep -i "Gateway alarm" /var/log/system.log' => 'gateway_alarms',
    // 'grep -i "GET" /var/log/system.log' => 'attacks',
    'grep -i "Successful login for user" /var/log/system.log' => 'successful_logins',
    'grep -i "Authentication error" /var/log/system.log' => 'auth_errors'
    // 'grep -i "REGISTER_SELF" /var/log/portalauth.log' => 'register_self',
    // 'grep -i "authenticated" /var/log/openvpn.log' => 'openvpn_auth' //?
];
$sayac= 0;
foreach ($commands as $command => $tableName) {
    $tablosayac = 0;
    if (true) {
    // if (isset($report[$tableName . '_checkbox']) && $report[$tableName . '_checkbox'] === 'on') {
        $output = shell_exec($command);
        $logLines = explode("\n", trim($output));
        foreach ($logLines as $logLine) {
            if (!empty($logLine)) {

                preg_match('/^([A-Za-z]{3})\s+(\d{1,2})\s+(\d{2}:\d{2}:\d{2})\s+(.*)$/', $logLine, $matches);
                $month = $matches[1];
                $day = $matches[2];
                $time = $matches[3];
                $logMessage = $matches[4];

                $monthNumber = monthToNumber($month);

                if ($monthNumber !== null) {
                    $currentYear = date("Y");
                    $logDate = $currentYear . '-' . $monthNumber . '-' . $day . ' ' . $time;
                    $checkStmt = $db->prepare("SELECT COUNT(*) FROM $tableName WHERE log_date = :log_date AND log_message = :log_message");
                    $checkStmt->bindValue(':log_date', $logDate, SQLITE3_TEXT);
                    $checkStmt->bindValue(':log_message', $logMessage, SQLITE3_TEXT);
                    $result = $checkStmt->execute();

                    $count = $result->fetchArray(SQLITE3_ASSOC)['COUNT(*)'];
                    if ($count == 0) {
                        $stmt = $db->prepare("INSERT INTO $tableName (log_date, log_message) VALUES (:log_date, :log_message)");
                        $stmt->bindValue(':log_date', $logDate, SQLITE3_TEXT);
                        $stmt->bindValue(':log_message', $logMessage, SQLITE3_TEXT);
                        $insertResult = $stmt->execute();
                    }
                    // if($config['report']['report_mail'] == 'on' && $count !== 0){
                    //     if($tablosayac==0){
                    //         $table_header ="<br> <br>". strtoupper(str_replace('_', ' ', $tableName)) . " TABLE";
                    //         $log_mail .= $table_header;
                    //         $tablosayac++;
                    //     }
                    //     $log_mail .= "<br>" . $logLine ;
                    // }
                }
            }
        }
    }
}
// if($config['report']['report_mail']=='on' && isset($config['report']['report_mail_profile']) && $config['report']['report_mail_profile'] != "none"){
//     $log_mail = isset($log_mail) ? $log_mail : 'Test Mail';

// 	if(!empty($log_mail)){
// 		$baslik="Analiz Raporu";
// 		$log_mail = "Analiz Raporu!". $log_mail;
// 		$active_profileId = $config['report']['report_mail_profile'];
// 		if(isset($active_profileId)){
// 			// mail_fonksiyonuzmuz_olucak($active_profileId,$log_mail,null,false,$baslik);
// 		}
// 	}
// }



?>
