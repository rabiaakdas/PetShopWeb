# Petiverse

Petiverse, PHP, HTML, CSS ve JavaScript ile hazırlanmış bir pet shop web uygulamasıdır. Uygulamada ürün listeleme, kategori değiştirme, kullanıcı kaydı, giriş, oturum yönetimi ve sepet işlemleri için PHP dosyaları bulunur.

# Genel Bakış

Proje, `php/index.php` dosyasını ana sayfa olarak kullanır. Ana sayfada kedi, köpek ve kuş ürünleri statik olarak listelenir; ürün ekleme işlemi JavaScript `fetch` isteği ile `php/addToCart.php` dosyasına gönderilir.

Kullanıcı kaydı ve girişi `mysqli` ile MySQL veritabanına bağlanan PHP dosyaları üzerinden yapılır. Sepet tarafında hem oturum tabanlı hem çerez tabanlı akışlar vardır; sipariş verme işlemi `php/sepet_islem.php` içinde veritabanı transaction kullanılarak işlenir.

# Öne Çıkan Mimari Özellikler

- PHP dosyaları doğrudan HTTP giriş noktası olarak çalışır.
- Ana sayfa, ürün kartlarını statik HTML içinde tutar.
- Kategori geçişleri istemci tarafında `js.js` içindeki `showCategory` fonksiyonu ile yapılır.
- Sepete ürün ekleme işlemi `fetch` ve `FormData` ile `POST` olarak gönderilir.
- Kullanıcı kaydı `password_hash` ile parolayı hashleyerek `kullanicilar` tablosuna yazar.
- Kullanıcı girişi `password_verify` ile parola doğrulaması yapar.
- Oturum bilgileri PHP `$_SESSION` üzerinde tutulur.
- Girişsiz sepet akışı bazı dosyalarda `sepet` çerezi ile desteklenir.
- Sipariş oluşturma akışı `begin_transaction`, `commit` ve `rollback` çağrılarını kullanır.
- Veritabanı erişimi `mysqli` ve prepared statement kullanılarak yapılır.

# Kullanılan Teknolojiler

| Alan | Teknoloji |
| --- | --- |
| Sunucu tarafı | PHP |
| Veritabanı erişimi | mysqli |
| Veritabanı | MySQL |
| İstemci tarafı | JavaScript |
| Sayfa işaretleme | HTML |
| Stil | CSS |
| UI kütüphanesi | Bootstrap 5.3.0 CDN |
| İkon | Font Awesome 6.6.0 CDN |
| Oturum | PHP Session |
| Çerez | PHP `setcookie`, JavaScript `document.cookie` |

# Proje Yapısı

```text
PetShopWeb-main/
├── README.md                  # Proje dokümantasyonu
├── js.js                      # Menü, kategori, çerez ve sepet istekleri
├── css/                       # Sayfa stilleri
│   └── *.css                  # Ana sayfa ve statik sayfa stilleri
├── html/                      # Statik içerik sayfaları ve bazı görseller
│   ├── hakkimizda.html        # Hakkımızda sayfası
│   ├── hizmetler.html         # Hizmetler sayfası
│   ├── calismalarimiz.html    # Çalışmalarımız sayfası
│   └── *.webp                 # Sayfa görselleri
├── php/                       # PHP giriş noktaları ve veritabanı bağlantıları
│   ├── index.php              # Ana sayfa
│   ├── login.php              # Giriş formu ve doğrulama
│   ├── kayit.php              # Kullanıcı kayıt formu
│   ├── profile.php            # Oturumdaki kullanıcı bilgisi
│   ├── addToCart.php          # Oturum sepetine ürün ekleme
│   ├── add_to_cart.php        # Kullanıcı/çerez sepetine ürün ekleme
│   ├── view_cart.php          # Sepet görüntüleme
│   ├── sepet_islem.php        # Sepet listeleme, silme ve sipariş verme
│   ├── baglanti.php           # `$baglanti` MySQL bağlantısı
│   ├── db_connection.php      # `$conn` MySQL bağlantısı
│   ├── logout.php             # Oturumu sonlandırma
│   └── cikis.php              # Oturumu sonlandırma
└── resim/                     # Ürün ve slider görselleri
    └── *.jpg, *.png           # Ürün görselleri
```

# İstek Akışı

```text
HTTP Request
↓
PHP dosyası
↓
Form verisi / Query string / Session / Cookie kontrolü
↓
Validation
↓
mysqli prepared statement
↓
MySQL
↓
Session / Cookie / HTML / JSON response
↓
HTTP Response
```

# Gereksinimler

- PHP çalıştırma ortamı
- MySQL sunucusu
- `mysqli` eklentisi
- Tarayıcı
- PHP dosyalarını sunabilecek yerel web sunucusu
- `petshop` adlı MySQL veritabanı

# Kurulum

1. Projeyi yerel makineye alın.

```bash
git clone <repo-url>
cd PetShopWeb-main
```

2. MySQL üzerinde `petshop` adlı veritabanını oluşturun.

```sql
CREATE DATABASE petshop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

3. PHP bağlantı dosyalarındaki veritabanı bilgilerini kontrol edin.

```php
$host = "localhost";
$kullanici = "root";
$parola = "";
$vt = "petshop";
```

4. Projeyi PHP yerleşik sunucusu ile çalıştırın.

```bash
php -S localhost:8000 -t .
```

5. Ana sayfayı tarayıcıda açın.

```text
http://localhost:8000/php/index.php
```

# Docker

Bu depoda `Dockerfile`, `docker-compose.yml` veya `.dockerignore` bulunmuyor.

# Testler

Bu depoda otomatik test projesi veya test komutu bulunmuyor.

| Test türü | Durum | Açıklama |
| --- | --- | --- |
| Unit test | Yok | Test dosyası bulunmuyor |
| Integration test | Yok | Test altyapısı bulunmuyor |
| UI test | Yok | Tarayıcı test dosyası bulunmuyor |
| PHP syntax kontrolü | Elle çalıştırılabilir | PHP kurulu ortamda `php -l` kullanılabilir |

PHP söz dizimi kontrolü için örnek komut:

```bash
php -l php/index.php
php -l php/login.php
php -l php/kayit.php
php -l php/sepet_islem.php
```

# Test Kullanıcıları

Kodda seed dosyası, SQL dump veya hazır test kullanıcısı oluşturan bir betik bulunmuyor.

# API Endpointleri

Projede REST API controller yapısı bulunmuyor. Aşağıdaki tablo, HTTP ile erişilebilen PHP ve HTML giriş noktalarını gösterir.

| Method | Route | Açıklama | Yetki |
| --- | --- | --- | --- |
| GET | `/php/index.php` | Ana sayfayı ve ürün listesini gösterir | Herkese açık |
| GET | `/php/index.php?logout=true` | Oturumu ve bazı çerezleri temizleyip ana sayfaya yönlendirir | Oturum varsa |
| GET | `/php/login.php` | Giriş formunu gösterir | Herkese açık |
| POST | `/php/login.php` | `kullaniciadi` ve `parola` ile giriş yapar | Herkese açık |
| GET | `/php/kayit.php` | Kayıt formunu gösterir | Herkese açık |
| POST | `/php/kayit.php` | Kullanıcı adı, e-posta ve parola ile kullanıcı oluşturur | Herkese açık |
| GET | `/php/profile.php` | Oturumdaki kullanıcı adını ve e-postayı gösterir | Session |
| POST | `/php/addToCart.php` | `urun_id` ve `miktar` ile ürünü oturum sepetine ekler | Herkese açık |
| POST | `/php/add_to_cart.php` | `product_id`, `price`, `quantity` ile sepet kaydı oluşturur veya çerez sepetini günceller | Session veya Cookie |
| GET | `/php/view_cart.php` | Session veya çerez sepetini listeler | Session veya Cookie |
| GET | `/php/sepet_islem.php` | Oturum sepetini görüntüler | Herkese açık |
| POST | `/php/sepet_islem.php` | E-posta kaydeder veya sipariş oluşturur | Session veya e-posta formu |
| GET | `/php/sepet_islem.php?remove_id={id}` | Oturum sepetinden ürün siler | Session sepeti |
| GET | `/php/logout.php` | Oturumu temizleyip giriş sayfasına yönlendirir | Session |
| GET | `/php/cikis.php` | Oturumu temizleyip giriş sayfasına yönlendirir | Session |

# Veritabanı

Projede migration, SQL dump veya tablo oluşturma betiği bulunmuyor. Aşağıdaki tablo, PHP kodunda doğrudan referans verilen MySQL tablolarını listeler.

| Entity | Açıklama |
| --- | --- |
| `kullanicilar` | Kullanıcı adı, e-posta ve parola hash bilgisinin okunduğu/yazıldığı tablo |
| `urunlerim` | `addToCart.php` içinde ürün adı, fiyat ve görsel bilgisi için sorgulanır |
| `sepetim` | `addToCart.php` içinde ürün ve miktar bilgisinin yazıldığı tablo |
| `products` | `view_cart.php` içinde ürün adı ve fiyat bilgisi için kullanılır |
| `sepet1` | `add_to_cart.php` ve `view_cart.php` içinde kullanıcı sepeti için kullanılır |
| `siparisler` | `sepet_islem.php` içinde sipariş başlığı için kullanılır |
| `siparis_urunleri` | `sepet_islem.php` içinde sipariş satırları için kullanılır |
| `urunler` | `sepet_islem.php` içinde stok kontrolü ve stok güncelleme için kullanılır |

# Konfigürasyon

Veritabanı bağlantı bilgileri PHP dosyalarında sabit değişkenler olarak tutulur. `appsettings`, `.env` veya JSON konfigürasyon dosyası bulunmuyor.

| Dosya | Bağlantı Değişkeni | Veritabanı |
| --- | --- | --- |
| `php/baglanti.php` | `$baglanti` | `petshop` |
| `php/db_connection.php` | `$conn` | `petshop` |
| `php/addToCart.php` | `$conn` | `petshop` |
| `php/sepet_islem.php` | `$conn` | `petshop` |

Kodda kullanılan değerlerin belgelenmiş gösterimi:

```json
{
  "database": {
    "host": "localhost",
    "username": "root",
    "password": "<secret>",
    "name": "petshop"
  },
  "session": {
    "enabled": true
  },
  "cookies": {
    "user_preference_days": 30,
    "cart_cookie": "sepet"
  }
}
```

# NuGet Paketleri

Depoda `.csproj` dosyası bulunmadığı için NuGet paketi yoktur. Ayrıca `composer.json` ve `package.json` dosyaları da bulunmuyor.

# .NET Dosya Analizi

Kullanıcı isteğinde listelenen .NET dosya ve klasörleri depoda bulunmuyor.

| İncelenen Öğe | Durum |
| --- | --- |
| Solution, `.csproj`, `Program.cs` | Yok |
| `appsettings`, `launchSettings` | Yok |
| Controllers, Api Controllers, Areas | Yok |
| Services, Interfaces, Repositories | Yok |
| Entity sınıfları, DTO, ViewModels | Yok |
| Validators, Middleware, Extensions | Yok |
| Migrations, Test projesi, GitHub Actions | Yok |

# Lisans

Bu proje eğitim amacıyla geliştirilmiştir.
