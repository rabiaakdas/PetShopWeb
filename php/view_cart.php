<?php
session_start();
include('db_connection.php'); // Veritabanı bağlantısını sağlamak için

// Sepet görüntüleme işlemi
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Kullanıcı ID'si oturumdan alınır

    // Sepet verilerini almak için sorgu
    $query = "SELECT p.name, p.price, s.quantity FROM sepet1 s JOIN products p ON s.product_id = p.id WHERE s.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Sepetiniz</h2>";
        echo "<table class='table'>";
        echo "<thead><tr><th>Ürün Adı</th><th>Miktar</th><th>Fiyat</th><th>Toplam</th></tr></thead>";
        echo "<tbody>";

        $toplam_fiyat = 0;

        while ($row = $result->fetch_assoc()) {
            $toplam_tutar = $row['price'] * $row['quantity'];
            $toplam_fiyat += $toplam_tutar;

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $row['price'] . "₺</td>";
            echo "<td>" . $toplam_tutar . "₺</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "<p><strong>Toplam Tutar: " . $toplam_fiyat . "₺</strong></p>";
    } else {
        echo "<p>Sepetinizde ürün bulunmamaktadır.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Kullanıcı giriş yapmamışsa çerezdeki sepeti görüntüle
    if (isset($_COOKIE['sepet'])) {
        $sepet = json_decode($_COOKIE['sepet'], true);

        if (!empty($sepet)) {
            echo "<h2>Sepetiniz</h2>";
            echo "<table class='table'>";
            echo "<thead><tr><th>Ürün Adı</th><th>Miktar</th><th>Fiyat</th><th>Toplam</th></tr></thead>";
            echo "<tbody>";

            $toplam_fiyat = 0;

            foreach ($sepet as $item) {
                // Ürün bilgilerini almak için
                $product_id = $item['product_id'];
                $price = $item['price'];
                $quantity = $item['quantity'];

                // Ürün adını veritabanından almak
                $query = "SELECT name FROM products WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $stmt->bind_result($product_name);
                $stmt->fetch();
                $stmt->close();

                $toplam_tutar = $price * $quantity;
                $toplam_fiyat += $toplam_tutar;

                echo "<tr>";
                echo "<td>" . htmlspecialchars($product_name) . "</td>";
                echo "<td>" . $quantity . "</td>";
                echo "<td>" . $price . "₺</td>";
                echo "<td>" . $toplam_tutar . "₺</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "<p><strong>Toplam Tutar: " . $toplam_fiyat . "₺</strong></p>";
        } else {
            echo "<p>Sepetinizde ürün bulunmamaktadır.</p>";
        }
    } else {
        echo "<p>Sepetinizde ürün bulunmamaktadır.</p>";
    }
}
?>
