# Panduan Arsitektur & Struktur Folder - OPTIK FOCUS

Aplikasi web **Pencatatan Rekam Medis Optik Focus** ini dirancang menggunakan **Arsitektur Antigravity (Anti-Gravity)**. Arsitektur ini mengedepankan prinsip **Single Entry Point**, pemisahan logika bisnis (**MVC - Model View Controller**), serta keamanan data dengan menyimpan database SQLite dan file inti aplikasi di luar direktori akses publik.

---

## 📂 Struktur Direktori Project (Antigravity Layout)

Berikut adalah struktur folder yang telah diimplementasikan:

```text
irt/
├── app/
│   ├── Config/
│   │   └── Database.php      <-- Koneksi PDO SQLite (Aman & Self-Initializing)
│   ├── Controllers/
│   │   ├── DashboardController.php <-- Logika analitik & ringkasan dashboard
│   │   └── TransactionController.php <-- Logika CRUD pencatatan transaksi
│   ├── Models/
│   │   └── Transaction.php    <-- Query terproteksi SQL Injection (PDO Prepared)
│   └── Core/
│       └── Router.php        <-- Router Native PHP (GET/POST & Clean URL)
├── database/
│   └── keuangan.db           <-- Database SQLite (Aman dari akses browser langsung)
├── public/
│   ├── css/
│   │   └── style.css         <-- Core Design System (Premium Vanilla CSS)
│   ├── js/
│   └── index.php             <-- Bootstrapper & Single Entry Point
├── views/
│   ├── dashboard.php         <-- UI Dashboard (KPI Cards & visual progress alokasi)
│   ├── transactions/
│   │   └── index.php         <-- UI Transaksi & Form input interaktif
│   └── templates/
│       ├── header.php        <-- Template Navigasi Sidebar & Flash Banner
│       └── footer.php        <-- Template Penutup layout
├── .htaccess                 <-- Rewrite Apache untuk memetakan root ke public/
└── architecture_guide.md     <-- File panduan arsitektur (Dokumen ini)
```

---

## 🛡️ Fitur Keamanan & Desain Unggulan

1. **Single Entry Point (`public/index.php`)**:
   Seluruh request dari browser dialihkan ke file `public/index.php` menggunakan aturan `.htaccess` dua tingkat. File di luar folder `public/` (seperti core, controller, model, dan file database) terisolasi secara aman dan tidak dapat diakses langsung oleh browser.
   
2. **Koneksi Database PDO SQLite Aman (`app/Config/Database.php`)**:
   Koneksi SQLite dikonfigurasi menggunakan PDO dengan mode deteksi error `PDO::ERRMODE_EXCEPTION` serta pengaktifan batasan relasi asing (`PRAGMA foreign_keys = ON`). Jika file database `keuangan.db` belum ada, sistem akan otomatis membuat tabel dan mengisi data awal secara dinamis.

3. **Proteksi SQL Injection & XSS (`app/Models/Transaction.php`)**:
   Semua fungsi *Create, Read, Update, Delete* (CRUD) menggunakan **Prepared Statements** (`$db->prepare` dan `$stmt->execute`). Data teks yang masuk juga disaring menggunakan `htmlspecialchars()` untuk melindungi dari ancaman XSS (*Cross-Site Scripting*).

4. **Desain Visual Premium (`public/css/style.css`)**:
   Mengusung desain modern minimalis dengan tipografi Google Fonts (`Outfit` & `Plus Jakarta Sans`), ikon beresolusi tinggi (`Ionicons`), transisi hover yang halus, glassmorphism card, bar persentase alokasi belanja yang dinamis, serta navigasi sidebar responsif.

---

## 🏛️ Alur Kerja Arsitektur (Data Flow)

```
[Browser Request: /transactions]
               │
               ▼
        [Root .htaccess]
               │
               ▼
      [public/.htaccess]
               │
               ▼
   [public/index.php Entry]
               │
               ▼
   [Custom PSR-4 Autoloader] ──► [app/Core/Router.php Dispatcher]
                                              │
                                              ▼
                             [app/Controllers/TransactionController.php]
                                              │
                                              ▼
                                  [app/Models/Transaction.php]
                                              │
                                              ▼
                             [(database/keuangan.db SQLite)]
                                              │
                                              ▼
                                 [Render HTML Templates]
                                 ├── views/templates/header.php
                                 ├── views/transactions/index.php
                                 └── views/templates/footer.php
                                              │
                                              ▼
                                  [Sent back to Browser]
```

---

## 🚀 Cara Menjalankan Aplikasi

Aplikasi ini siap digunakan langsung di server XAMPP Anda:

1. Pastikan folder project berada di dalam direktori `C:\xampp\htdocs\irt\`.
2. Aktifkan modul **Apache** pada control panel XAMPP Anda.
3. Buka browser kesayangan Anda dan akses URL:
   ```text
   http://localhost/irt/
   ```
4. Aplikasi akan otomatis menginisialisasi database SQLite baru dengan data simulasi yang realistis, sehingga dashboard dan riwayat transaksi langsung terisi dengan visualisasi alokasi belanja yang menawan.

---

## 🛠️ Pengembangan Fitur CRUD di Masa Depan

Struktur boilerplate ini didesain agar sangat mudah dikembangkan. Berikut panduan singkat jika Anda ingin menambahkan modul baru (misal: Kelola Kategori):

1. **Buat Model Baru**:
   Tulis file `app/Models/Category.php` dengan namespace `App\Models` untuk mengurus query penambahan/penghapusan kategori.
   
2. **Buat Controller Baru**:
   Buat `app/Controllers/CategoryController.php` dengan method `index()`, `store()`, dan `destroy()`.
   
3. **Daftarkan Rute di `public/index.php`**:
   ```php
   $router->get('/categories', [CategoryController::class, 'index']);
   $router->post('/categories/create', [CategoryController::class, 'store']);
   ```
4. **Buat Tampilan UI**:
   Letakkan file tampilan di `views/categories.php` dan panggil dari `CategoryController`.
