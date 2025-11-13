<?php
session_start();
include('db_connection.php'); // Veritabanı bağlantısını sağlamak için

// Sepet ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // POST verileri kontrol et
    if (isset($_POST['product_id'], $_POST['price'], $_POST['quantity'])) {
        $product_id = $_POST['product_id'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        // Kullanıcı oturum kontrolü
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id']; // Kullanıcı ID'si oturumdan alınır

            // Ürün zaten sepette var mı kontrol et
            $query = "SELECT * FROM sepet1 WHERE user_id = ? AND product_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Ürün sepette var, miktarı güncelle
                $row = $result->fetch_assoc();
                $new_quantity = $row['quantity'] + $quantity;

                $update_query = "UPDATE sepet1 SET quantity = ?, price = ? WHERE user_id = ? AND product_id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("idii", $new_quantity, $price, $user_id, $product_id);
                $update_stmt->execute();

                echo "Ürün sepette güncellendi!";
            } else {
                // Ürün sepette yok, yeni ürün ekle
                $insert_query = "INSERT INTO sepet1 (user_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("iiid", $user_id, $product_id, $quantity, $price);
                $insert_stmt->execute();

                echo "Sepete başarıyla eklendi!";
            }
        } else {
            // Kullanıcı giriş yapmamışsa, çerezde sepeti sakla
            if (isset($_COOKIE['sepet'])) {
                $sepet = json_decode($_COOKIE['sepet'], true);
            } else {
                $sepet = [];
            }

            // Ürünü sepete ekle
            $found = false;
            foreach ($sepet as &$item) {
                if ($item['product_id'] == $product_id) {
                    // Ürün zaten sepette var, miktarı güncelle
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                // Ürün sepette yok, yeni ürün ekle
                $sepet[] = [
                    'product_id' => $product_id,
                    'price' => $price,
                    'quantity' => $quantity
                ];
            }

            // Çerezi güncelle
            setcookie('sepet', json_encode($sepet), time() + (86400 * 30), "/"); // 30 gün boyunca geçerli
            echo "Sepete başarıyla eklendi!";
        }
    } else {
        echo "Ürün bilgileri eksik!";
    }
}
?>
