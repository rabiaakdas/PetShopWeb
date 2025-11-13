<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "petshop";

// Veritabanı bağlantısını oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sepet kontrolü
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Ürün bilgileri
$urun_id = $_POST['urun_id'];
$miktar = $_POST['miktar'];

// Ürünü veritabanından al
$sql = "SELECT isim, fiyat, resim FROM urunlerim WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $urun_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    $isim = $product['isim'];
    $fiyat = $product['fiyat'];
    $resim = $product['resim'];

    // Ürünü sepete ekle
    if (isset($_SESSION['cart'][$urun_id])) {
        // Eğer ürün zaten sepette varsa, miktarı güncelle
        $_SESSION['cart'][$urun_id]['miktar'] += $miktar;
    } else {
        // Eğer ürün sepette yoksa, yeni bir giriş ekle
        $_SESSION['cart'][$urun_id] = [
            'isim' => $isim,
            'fiyat' => $fiyat,
            'resim' => $resim,
            'miktar' => $miktar
        ];
    }

    // Veritabanına ürünü eklemek için sepetim tablosuna veri ekleyebiliriz:
    $sql_sepet = "INSERT INTO sepetim (urun_id, miktar) VALUES (?, ?) 
                  ON DUPLICATE KEY UPDATE miktar = miktar + VALUES(miktar)";
    $stmt_sepet = $conn->prepare($sql_sepet);
    $stmt_sepet->bind_param("ii", $urun_id, $miktar);
    $stmt_sepet->execute();

    // Yanıt olarak sepet durumunu döndürebiliriz
    echo json_encode($_SESSION['cart']);
} else {
    // Ürün bulunamadı
    echo json_encode(["error" => "Ürün bulunamadı."]);
}

// Bağlantıyı kapat
$conn->close();
?>
