<?php
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["giris"])) {
    $name = trim($_POST["kullaniciadi"]);
    $parola = trim($_POST["parola"]);

    // Kullanıcı adı ve şifre dolu mu kontrol et
    if (!empty($name) && !empty($parola)) {
        // Kullanıcı adı sorgusu
        $stmt = $baglanti->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
        $stmt->bind_param("s", $name); // Kullanıcı adını bağla
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $ilgilikayit = $result->fetch_assoc();
            $hashlisifre = $ilgilikayit["parola"];

            // Şifre doğrulama
            if (password_verify($parola, $hashlisifre)) {
                session_start();
                $_SESSION["kullanici_adi"] = $ilgilikayit["kullanici_adi"];
                $_SESSION["email"] = $ilgilikayit["email"];
                header("Location: index.php");
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Hatalı şifre.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Kullanıcı bulunamadı.</div>';
        }
        $stmt->close();
    } else {
        echo '<div class="alert alert-danger" role="alert">Lütfen tüm alanları doldurun.</div>';
    }
    $baglanti->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Giriş</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white text-center">
            <h3>Giriş</h3>
          </div>
          <div class="card-body">
            <form action="login.php" method="post" autocomplete="off">
              <div class="mb-3">
                <label for="username" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="username" name="kullaniciadi" placeholder="Kullanıcı adınızı girin" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="password" name="parola" placeholder="Şifrenizi girin" required>
              </div>
              <div class="d-grid">
                <button type="submit" name="giris" class="btn btn-primary">Giriş Yap</button>
              </div>
            </form>
            <div class="mt-3 text-center">
              <a href="kayit.php" class="btn btn-secondary">Üye Ol</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>