<?php
require_once("func.inc");

// Bağlantıyı aç
$db = report_connect_db();

// Listelemek istediğin tablolar
$tables = ['successful_logins', 'auth_errors', 'register'];

foreach ($tables as $table) {
    echo "<h2>Table: $table</h2>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Date</th><th>Message</th></tr>";

    // $results = $db->query("SELECT * FROM $table ORDER BY id DESC");
    $results = $db->query("SELECT * FROM $table ORDER BY id ASC");

    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['log_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['log_message']) . "</td>";
        echo "</tr>";
    }

    echo "</table><br><br>";
}
?>
