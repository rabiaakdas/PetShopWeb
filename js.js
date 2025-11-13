function toggleSidebar() {
    document.getElementById("leftSidebar").classList.toggle("active");
}

function closeSidebar() {
    var sidebar = document.getElementById("left-sidebar");
    sidebar.style.display = "none"; // Sidebar'ı gizler
}

function showCategory(category) {
    const categories = document.querySelectorAll('.product-category');
    categories.forEach((cat) => {
        cat.classList.remove('active'); // Tüm kategorileri gizle
    });
    const selectedCategory = document.getElementById(`${category}-products`);
    if (selectedCategory) {
        selectedCategory.classList.add('active'); // Seçilen kategoriyi göster
    }
}

showCategory('cat');


// Giriş ve Kayıt arasında geçiş
const container = document.querySelector(".container");
const signUpLink = document.querySelector(".signup-link");
const loginLink = document.querySelector(".login-link");

// Kayıt formuna geçiş
signUpLink.addEventListener("click", (e) => {
    e.preventDefault(); // Sayfa yenilemeyi engelle
    container.classList.add("active");
});

// Giriş formuna geri dönüş
loginLink.addEventListener("click", (e) => {
    e.preventDefault(); // Sayfa yenilemeyi engelle
    container.classList.remove("active");
});


function addToCart(urunId, fiyat) {
    var miktar = document.querySelector('input[data-id="' + urunId + '"]').value;

    var formData = new FormData();
    formData.append('urun_id', urunId);
    formData.append('fiyat', fiyat);
    formData.append('miktar', miktar);

    fetch('addToCart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert('Sepete eklendi!');
        console.log(data); // Sepet güncel durumu konsolda gör
    })
    .catch(error => {
        console.error('Hata:', error);
    });
}


// Çerez kabul fonksiyonu
function acceptCookies() {
    setCookie('user_preference', 'light', 30); // Tercih bilgisini sakla
    document.getElementById('cookie-consent-popup').style.display = 'none';
}

// Çerez reddetme fonksiyonu
function declineCookies() {
    document.getElementById('cookie-consent-popup').style.display = 'none';
}

// Çerez ayarları
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function closeAd() {
    document.getElementById("ad-container").style.display = "none";
}



function closePopup() {
    document.getElementById('popup-ad').style.display = 'none';
}
