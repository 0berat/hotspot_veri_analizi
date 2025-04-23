<!DOCTYPE html>
<html>
<head>
    <title>Hoş Geldiniz!</title>
</head>
<body>
    <h1>Hoş Geldiniz!</h1>

    <?php
        // İsim değişkenini kontrol edelim
        if(isset($_POST['isim'])) {
            $isim = $_POST['isim'];
            echo "<p>Merhaba, $isim! İyi ki buradasınız.</p>";
        } else {
            echo "<p>Lütfen isminizi girin.</p>";
        }
    ?>

    <form method="POST" action="welcome.php">
        <label for="isim">İsminiz:</label>
        <input type="text" name="isim" id="isim" required>
        <button type="submit">Gönder</button>
    </form>
</body>
</html>
