<?php
require_once("func.inc");
// require_once("coslat-utils.inc");
// require_once("widget.inc");

error_reporting(E_ALL);
ini_set('display_errors', 1);
$config = load_config();
// init_config_arr(array('report'));
// global $config;
$report = &$config['report'];

$input_errors = [];

// var_dump($report);die();


// $tables = array("gateway_alarms", "attacks", "successful_logins", "auth_errors", "register_self", "openvpn_auth");
// var_dump($_POST);die();

if ($_POST) {
    $pconfig = $_POST;

    $report['report'] = isset($pconfig['report']) ? $pconfig['report'] : 'off';
    // var_dump($_POST);die();

    // $report['report_mail'] = isset($pconfig['report_mail']) ? 'on' : false;

    // $report['report_mail_profile'] = isset($pconfig['report_mail_profile']) ? $pconfig['report_mail_profile']  : "none"; // burayi default nnone getiririz.
    // $report['report_time'] = isset($pconfig['report_time']) ? $pconfig['report_time'] : null;

    // Checkbox'ların durumunu kaydet
    // foreach ($tables as $table) {
    //     $report[$table . '_checkbox'] = isset($pconfig[$table . '_checkbox']) ? 'on' : false;
    // }

    if (!$input_errors) {
		// $db = report_connect_db();
        write_config("report cron updated");
        header("Location: status_report.php");
        exit();
    }
}

$pconfig['report'] = isset($report['report']) ? $report['report'] : false;
// $pconfig['report_mail'] = isset($report['report_mail']) ? $report['report_mail'] : false;
// $pconfig['report_mail_profile'] = isset($report['report_mail_profile']) ? $report['report_mail_profile'] : "none";
// $pconfig['report_time'] = isset($report['report_time']) ? $report['report_time'] : null;

// foreach ($tables as $table) {
//     $pconfig[$table . '_checkbox'] = isset($report[$table . '_checkbox']) ? $report[$table . '_checkbox'] : false;
// }

// include("head.inc");
// $tab_array = array();
// $tab_array[] = array(gettext("Report Settings"),true ,($logfile == 'system'), "/status_report.php");
// $tab_array[] = array(gettext("Report Details"), ($logfile == 'system'), "/status_report_details.php");
// display_top_tabs($tab_array);

if ($input_errors) {
    // print_input_errors($input_errors);
}




?>
<form method="post">
    <label for="report">Enable Report Generation</label>
    <input type="hidden" name="report" value="off">
    <input type="checkbox" name="report" id="report" <?php echo ($pconfig['report'] === 'on') ? 'checked' : ''; ?> />

<!-- Diğer checkbox'ları da ekleyebilirsin aynı şekilde -->

    <button type="submit">Save Settings</button>
    </form>