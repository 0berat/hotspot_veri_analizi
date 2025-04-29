<?php
require_once "db_connect.php";

$email = "";
$name = "";
$hata = "";
$basarili = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $name = trim($_POST["name"]);

    if (empty($email) || empty($name)) {
        $hata = "Lütfen tüm alanları doldurun.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, "@gmail.com")) {
        $hata = "Lütfen geçerli bir Gmail adresi girin.";
    } else {
		$stmt = $db->prepare("INSERT INTO receiver (mail, name) VALUES (:mail, :name)");
		$stmt->execute([
		    ':mail' => $email,
		    ':name' => $name
		]);
        $basarili = "GMail adresi kaydedildi.";
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        return substr($haystack, -strlen($needle)) === $needle;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Alıcı Mail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow p-4" style="min-width: 350px; max-width: 400px;">
        <h4 class="text-center mb-4">📩 Alıcı Mail</h4>

        <?php if ($hata): ?>
            <div class="alert alert-danger"><?php echo $hata; ?></div>
        <?php endif; ?>

        <?php if ($basarili): ?>
            <div class="alert alert-success"><?php echo $basarili; ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Alıcı Gmail Adresi</label>
                <input type="email" class="form-control" id="email" name="email" required
                       value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Alıcı adı</label>
                <input type="text" class="form-control" id="name" name="name" required
                       value="<?php echo htmlspecialchars($name); ?>">
            </div>

            <button type="submit" class="btn btn-primary w-100">Onayla</button>
        </form>
    </div>
</div>

</body>
</html>
