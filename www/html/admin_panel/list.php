<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Listesi</title>
    <style>
        body{ font: 14px sans-serif; }

        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<?php
$referer = $_SERVER['HTTP_REFERER'];
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Oturum açılmamışsa veya oturum doğrulanmamışsa, admin.php'ye erişim izni verme
    header("location: ../admin_login.php");
    exit;
}

// İlk olarak veritabanına bağlanın (config.php dosyanıza göre).
require_once "../config.php";

// Kullanıcıları veritabanından alın
$sql = "SELECT * FROM radcheck";
$stmt = $pdo->query($sql);
?>

<h2 style="text-align: center;">Kullanıcı Listesi</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Kullanıcı Adı</th>
        <th>Parola</th>
        <th>kayit_tarihi</th>
        <th>TC NO</th>
    </tr>
    <?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["value"] . "</td>";
        echo "<td>" . $row["kayit_tarihi"] . "</td>";
        echo "<td>" . $row["tc_kimlik"] . "</td>";
        echo "</tr>";
    }
    unset($pdo);

    ?>
</table>


</body>
</html>






