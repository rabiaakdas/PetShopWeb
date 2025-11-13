<?php
// Session başlatılıyor
session_start();

// Kullanıcı girişi kontrol ediliyor
if (isset($_SESSION["kullanici_adi"])) {
    // Kullanıcı bilgileri ekrana yazdırılıyor
    echo "<h3>Hoşgeldiniz, " . $_SESSION["kullanici_adi"] . "!</h3>";
    echo "<h4>Email: " . $_SESSION["email"] . "</h4>";

    // Çıkış yapma bağlantısı
    echo "<a href='cikis.php' style='color:red; background-color:yellow; border:1px solid red; padding:5px 5px;'>ÇIKIŞ YAP</a>";
} else {
    // Eğer oturum açılmamışsa, uyarı mesajı
    echo "Bu sayfayı görüntüleme yetkiniz yoktur. Lütfen giriş yapın.";
}
?>
