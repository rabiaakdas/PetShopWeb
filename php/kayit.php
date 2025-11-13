<?php
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kaydet"])) {
    $name = trim($_POST["kullaniciadi"]);
    $email = trim($_POST["email"]);
    $password = password_hash(trim($_POST["parola"]), PASSWORD_DEFAULT);

    // Boş alan kontrolü
    if (!empty($name) && !empty($email) && !empty($password)) {
        // Kullanıcı adı eşsiz mi kontrol et
        $stmt_check = $baglanti->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
        $stmt_check->bind_param("s", $name);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo '<div class="alert alert-danger" role="alert">Bu kullanıcı adı zaten alınmış.</div>';
        } else {
            // Yeni kullanıcı ekle
            $stmt = $baglanti->prepare("INSERT INTO kullanicilar (kullanici_adi, email, parola) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
            $calistirekle = $stmt->execute();

            if ($calistirekle) {
                echo '<div class="alert alert-success" role="alert">Kayıt başarılı.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Kayıt eklenirken hata oluştu.</div>';
            }
            $stmt->close();
        }
        $stmt_check->close();
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
  <title>Kayıt Ol</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white text-center">
            <h3>Kayıt Ol</h3>
          </div>
          <div class="card-body">
            <form action="kayit.php" method="post" autocomplete="off">
              <div class="mb-3">
                <label for="username" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="username" name="kullaniciadi" placeholder="Kullanıcı adınızı girin" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">E-posta</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="E-posta adresinizi girin" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="password" name="parola" placeholder="Şifrenizi girin" required>
              </div>
              <div class="d-grid">
                <button type="submit" name="kaydet" class="btn btn-primary">Kayıt Ol</button>
              </div>
            </form>
            <p class="text-center mt-3">Hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
