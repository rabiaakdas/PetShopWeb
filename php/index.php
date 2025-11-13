<?php
// Oturum başlatmak için session_start kullanıyoruz
session_start();

// Kullanıcı tercihlerini çerezlerde sakla
if (!isset($_COOKIE['user_preference'])) {
    setcookie('user_preference', 'light', time() + (86400 * 30), "/"); // 30 gün geçerli
}



// Çıkış işlemi ve çerezleri silme
if (isset($_GET['logout'])) {
    setcookie('user_preference', '', time() - 3600, "/"); // Çerezi geçersiz kıl
    setcookie('remember_me', '', time() - 3600, "/"); // Çerezi geçersiz kıl
    session_destroy();
    header("Location: index.php"); // Çıkış sonrası yönlendirme
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Shop</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="./js.js" defer></script>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="resim.css">
</head>
<body>
    
<!-- Çerez Kabul Popup -->
<div id="cookie-consent-popup" style="position: fixed; bottom: 20px; left: 0; right: 0; background: #333; color: white; padding: 10px; text-align: center;">
    <p>Bu site, deneyiminizi geliştirmek için çerezler kullanmaktadır. Çerezleri kabul ediyor musunuz?</p>
    <button onclick="acceptCookies()">Kabul Et</button>
    <button onclick="declineCookies()">Reddet</button>
</div>

<button class="leftSidebarButton" onclick="toggleSidebar()">☰</button>

<div id="leftSidebar">
    <ul>
        <li><a href="#">Anasayfa</a></li>
        <li><a href="hakkimizda.html">Hakkımda</a></li>
        <li><a href="hizmetler.html">Hizmetler</a></li>
        <li><a href="calismalarimiz.html">Çalışmalarımız</a></li>
        <li><a href="#footer" onclick="closeSidebar()">İletişim</a></li>
    </ul>
</div>
<div class="kutu1">
    
    <h2 class="kayan-yazi">350 TL ve Üzeri Alışverişlerde Kargo Bedava</h2>
    
    
    <?php
        // Kullanıcı giriş durumu kontrolü
        // Mevcut oturum veya çerez bilgisi ile giriş durumu kontrolü
if (isset($_SESSION["kullanici_adi"])) {
    echo "<div class='user-info'>";
    echo "<h4>Email: " . $_SESSION["email"] . "</h4>";
    echo "<a href='?logout=true' style='color:red; background-color:yellow; border:1px solid red; padding:5px 5px;'>ÇIKIŞ YAP</a>";
    echo "</div>";
} else {
    // Eğer oturum yoksa, çerezlerden giriş bilgisini kontrol et
    if (isset($_COOKIE['remember_me'])) {
        // Çerezlerdeki değeri kullanarak kullanıcıyı otomatik giriş yap
        $_SESSION["kullanici_adi"] = $_COOKIE['remember_me'];
        echo "<div class='user-info'>";
        echo "<h4>Hoş geldiniz, " . $_SESSION["kullanici_adi"] . "!</h4>";
        echo "</div>";
    }
}
    ?>
</div>

<div class="header-icons">
    <a href="#" id="search-icon"><i class="fa-solid fa-magnifying-glass"></i></a>
    <div id="search-box" class="hidden">
        <input type="text" id="search-input" placeholder="Arama yap...">
    </div>
    <a href="login.php"><i class="fa-regular fa-user"></i></a>
    <a href="sepet_islem.php"><i class="fa-solid fa-basket-shopping"></i></a>
</div>

<div class="container">
<div class="kutu2">
    <h3>PETİVERSE</h3>
    <hr style="border: 1px solid black; width: 50%; margin: 0 auto;">
</div>

</div>



<div>
    <figure>
        <div class="slide">
            <a href="https://www.petaddress.com.tr/evcil-hayvanlar-nerede-satilir/">
                <img src="evcil-hayvnalar-nerede-satilir.jpg" alt="">
            </a>
        </div>
        <div class="slide">
            <a href="https://www.hazirticaretsitesi.com/blog/pet-shop-e-ticaret-paketi/">
                <img src="petshop.webp" alt="">
            </a>
        </div>
        <div class="slide">
            <a href="https://example3.com">
                <img src="yakamoz-petshop-atasehir.png" alt="">
            </a>
        </div>
        <div class="slide">
            <a href="https://example4.com">
                <img src="resim.jpg" alt="">
            </a>
        </div>
    </figure>
</div>



<div class="menu">
    <ul>
        <li><a href="#" onclick="showCategory('cat')">Kedi Ürünleri</a></li>
        <li><a href="#" onclick="showCategory('dog')">Köpek Ürünleri</a></li>
        <li><a href="#" onclick="showCategory('bird')">Kuş Ürünleri</a></li>
    </ul>
</div>

<section class="product" id="product">
    <div class="container">
    
        <!-- Kedi Ürünleri -->
        <div id="cat-products" class="product-category">
            <div class="p-card">
                <div class="img">
                    <img src="cat_food.jpg" alt="Kedi Maması">
                </div>
                <div class="p-content">
                    <h3>Kedi Maması / 500G</h3>
                    <p>Kaliteli ve lezzetli kedi mamaları.</p>
                    <p><strong>Fiyat: 100₺</strong></p>
                    <input type="number" class="miktar-giris" min="1" max="99" value="1" data-id="1">
                    <button class="sayfa-dugme" onclick="addToCart(1, 100)">Sepete Ekle</button>
                </div>
            </div>
            <div class="p-card">
                <div class="img">
                    <img src="royat-kedi.webp" alt="Kedi Oyuncağı">
                </div>
                <div class="p-content">
                    <h3>Kedi Oyuncağı</h3>
                    <p>Kedileriniz için eğlenceli oyuncaklar.</p>
                    <p><strong>Fiyat: 50₺</strong></p>
                    <input type="number" class="miktar-giris" min="1" max="99" value="1" data-id="2">
                    <button class="sayfa-dugme" onclick="addToCart(2, 50)">Sepete Ekle</button>
                </div>
            </div>
        </div>
        
        <!-- Köpek Ürünleri -->
        <div id="dog-products" class="product-category">
            <div class="p-card">
                <div class="img">
                    <img src="dog_food.jpg" alt="Köpek Maması">
                </div>
                <div class="p-content">
                    <h3>Köpek Maması / 1KG</h3>
                    <p>Sağlıklı ve besleyici köpek mamaları.</p>
                    <p><strong>Fiyat: 150₺</strong></p>
                    <input type="number" class="miktar-giris" min="1" max="99" value="1" data-id="3">
                    <button class="sayfa-dugme" onclick="addToCart(3, 150)">Sepete Ekle</button>
                </div>
            </div>
            <div class="p-card">
                <div class="img">
                    <img src="royal-kopek.jpg" alt="Köpek Oyuncağı">
                </div>
                <div class="p-content">
                    <h3>Köpek Oyuncağı</h3>
                    <p>Köpeğiniz için dayanıklı oyuncaklar.</p>
                    <p><strong>Fiyat: 80₺</strong></p>
                    <input type="number" class="miktar-giris" min="1" max="99" value="1" data-id="4">
                    <button class="sayfa-dugme" onclick="addToCart(4, 80)">Sepete Ekle</button>
                </div>
            </div>
        </div>

        <!-- Kuş Ürünleri -->
        <div id="bird-products" class="product-category">
            <div class="p-card">
                <div class="img">
                    <img src="goldwings-kus.jpg" alt="Kuş Yemi">
                </div>
                <div class="p-content">
                    <h3>Kuş Yemi / 500G</h3>
                    <p>Kanatlı dostlarınız için özel yemler.</p>
                    <p><strong>Fiyat: 40₺</strong></p>
                    <input type="number" class="miktar-giris" min="1" max="99" value="1" data-id="5">
                    <button class="sayfa-dugme" onclick="addToCart(5, 40)">Sepete Ekle</button>
                </div>
            </div>
            <div class="p-card">
                <div class="img">
                    <img src="jungle-kus.jpg" alt="Kuş Oyuncağı">
                </div>
                <div class="p-content">
                    <h3>Kuş Oyuncağı</h3>
                    <p>Kuşlar için doğal ve eğlenceli oyuncaklar.</p>
                    <p><strong>Fiyat: 30₺</strong></p>
                    <input type="number" class="miktar-giris" min="1" max="99" value="1" data-id="6">
                    <button class="sayfa-dugme" onclick="addToCart(6, 30)">Sepete Ekle</button>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Özellikler -->
<div class="features-container">
    <div class="feature-card">
        <div class="icon">©</div>
        <h3>%100 Orijinal</h3>
        <p>Her zaman resmi distribütörlerle çalışırız. Açık mama satışımız bulunmamaktadır.</p>
    </div>
    <div class="feature-card">
        <div class="icon">🚚</div>
        <h3>Kapıda Ödeme</h3>
        <p>İsterseniz kapıda nakit veya kredi kartıyla ödeyebilirsiniz.</p>
    </div>
    <div class="feature-card">
        <div class="icon">🎧</div>
        <h3>Aynı Gün Kargo</h3>
        <p>16:30’a kadar vermiş olduğunuz siparişler aynı gün kargoda kapıda ödeme seçeneğiyle sizlerle.</p>
    </div>
    <div class="feature-card">
        <div class="icon">🔄</div>
        <h3>Ücretsiz İade</h3>
        <p>Siparişinizi 15 gün içerisinde ücretsiz iade edebilirsiniz.</p>
    </div>
</div>

<footer class="footer" id="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Hakkımızda</h3>
                <p>Petiverse, evcil hayvan dostlarınız için en kaliteli ürünleri sunar. Güvenli alışveriş, hızlı teslimat ve müşteri memnuniyeti garantisi ile hizmetinizdeyiz.</p>
            </div>
            
            <div class="footer-section">
                <h3>İletişim</h3>
                <p><i class="fa-solid fa-envelope"></i> info@petiverse.com</p>
                <p><i class="fa-solid fa-phone"></i> +90 555 123 45 67</p>
                <p><i class="fa-solid fa-location-dot"></i> İstanbul, Türkiye</p>
            </div>
            <div class="footer-section social-media">
                <h3>Bizi Takip Edin</h3>
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Petiverse. Tüm hakları saklıdır.</p>
        </div>
    </footer>


    
</body>
</html>
