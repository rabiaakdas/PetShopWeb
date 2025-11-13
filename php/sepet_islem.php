<?php
session_start();

// Veritabanı bağlantısını oluştur
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "petshop";

// Veritabanı bağlantısını oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Email kontrolü: Kullanıcı giriş yaptıysa email'i al
$email = isset($_SESSION["email"]) ? $_SESSION["email"] : null;

if (isset($_SESSION["kullanici_adi"])) {
    echo "<div class='user-info'>";
    echo "<h4>Hoş geldiniz, " . htmlspecialchars($_SESSION["kullanici_adi"]) . "!</h4>";
    echo "<p>Email: " . htmlspecialchars($email) . "</p>"; // Email'i yazdır
    echo "</div>";
} else {
   // echo "<p>Henüz giriş yapmadınız. Alışverişe başlamadan önce giriş yapmanız gerekmektedir.</p>";
    //echo "<a href='login.php'>Giriş Yap</a>"; // Giriş yapmak için bir bağlantı
}

// Email girilmesi için form ekle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];
    $_SESSION["email"] = $email; // Email'i session'a kaydet
    echo "<p>Email başarıyla kaydedildi: " . htmlspecialchars($email) . "</p>";
}

// Silme işlemi
if (isset($_GET['remove_id'])) {
    $remove_id = $_GET['remove_id'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]); // Ürünü sepetten sil
        echo "<p>Ürün sepetinizden silindi.</p>";
    }
}

// Sipariş ver işlemi
if (isset($_POST['order'])) {
    if (!$email) {
        echo "<p>Email girmeniz gerekiyor. Lütfen önce bir email adresi girin.</p>";
    } else {
        $order_completed = true;

        // Veritabanı işlemlerini bir arada yapmak için işlem başlat
        $conn->begin_transaction();

        // Siparişi siparisler tablosuna ekle
        $insert_order_query = "INSERT INTO siparisler (kullanici_email) VALUES (?)";
        $stmt = $conn->prepare($insert_order_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $siparis_id = $stmt->insert_id; // En son eklenen siparis_id'sini al
        $stmt->close();

        // Sepetteki her ürünü işleme al
        foreach ($_SESSION['cart'] as $urun_id => $urun) {
            $urun_id = intval($urun_id);
            $miktar = intval($urun['miktar']);
            $toplam_fiyat = $urun['fiyat'] * $miktar;

            // Stok kontrolü yap
            $query = "SELECT stok FROM urunler WHERE urun_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $urun_id);
            $stmt->execute();
            $stmt->bind_result($stok);
            $stmt->fetch();
            $stmt->close();

            // Eğer stok yeterli değilse, siparişi iptal et
            if ($stok < $miktar) {
                $order_completed = false;
                echo "<p>Ürün " . htmlspecialchars($urun['isim']) . " için yeterli stok yok.</p>";
            } else {
                // Sipariş ürününü siparis_urunleri tablosuna ekle
                $insert_order_item_query = "INSERT INTO siparis_urunleri (siparis_id, urun_id, miktar, toplam_fiyat) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_order_item_query);
                $stmt->bind_param("iiid", $siparis_id, $urun_id, $miktar, $toplam_fiyat);
                $stmt->execute();
                $stmt->close();

                // Stok güncelle
                $new_stok = $stok - $miktar;
                $update_query = "UPDATE urunler SET stok = ? WHERE urun_id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("ii", $new_stok, $urun_id);
                $update_stmt->execute();
                $update_stmt->close();
            }
        }

        if ($order_completed) {
            $conn->commit(); // İşlem başarılıysa commit et
            echo "<p>Sipariş tamamlandı! Teşekkür ederiz.</p>";
            // Sepeti temizle
            unset($_SESSION['cart']);
        } else {
            $conn->rollback(); // İşlemde bir hata varsa rollback yap
            echo "<p>Sipariş tamamlanamadı.</p>";
        }
    }
}

// Sepet kontrolü
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<h2>Sepetinizdeki Ürünler:</h2>";
    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<thead>";
    echo "<tr>
        <th><b><i><u>Ürün ID</u></i></b></th>
        <th><b><i><u>Ürün Adı</u></i></b></th>
        <th><b><i><u>Fiyat</u></i></b></th>
        <th><b><i><u>Miktar</u></i></b></th>
        <th><b><i><u>Toplam Fiyat</u></i></b></th>
        <th><b><i><u>İşlem</u></i></b></th>
    </tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($_SESSION['cart'] as $urun_id => $urun) {
        echo "<tr>";
        echo "<td>" . $urun_id . "</td>";
        echo "<td>" . htmlspecialchars($urun['isim']) . "</td>";
        echo "<td>" . $urun['fiyat'] . "₺</td>";
        echo "<td>" . $urun['miktar'] . "</td>";
        echo "<td>" . $urun['fiyat'] * $urun['miktar'] . "₺</td>";
        // Silme butonu
        echo "<td><a href='?remove_id=" . $urun_id . "' onclick='return confirm(\"Emin misiniz?\")'>Sil</a></td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

    // Sipariş ver butonu
    echo "<form method='post'>
            <button type='submit' name='order'>Sipariş Ver</button>
          </form>";
} else {
    echo "<p>Sepetiniz boş.</p>";
}

// Eğer giriş yapılmamışsa email formu göster
if (!$email) {
    echo "<h3>Email adresinizi girin:</h3>";
    echo "<form method='post'>
            <input type='email' name='email' required>
            <button type='submit'>Email Kaydet ve Sipariş Ver</button>
          </form>";
}

// Bağlantıyı kapat
$conn->close();
?>
