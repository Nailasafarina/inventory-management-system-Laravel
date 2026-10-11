# InventPro - Inventory Management System (Laravel P5)

Mini Project 3: Aplikasi Inventory Management System bertema **Snack Corner**. Proyek ini merupakan hasil *refactoring* dan migrasi dari PHP Native (P4) ke framework Laravel (P5) dengan tetap mempertahankan aturan bisnis (*business logic*) stok barang.

---

## 🏗️ Penjelasan Arsitektur MVC Proyek
Sesuai dengan capaian pembelajaran, aplikasi ini telah menerapkan pola desain **Model-View-Controller (MVC)** beserta alur kerja Laravel (`Request → Route → Controller → Model → Database → Response`):

1. **Model (M)**: Menggunakan Eloquent ORM (`app/Models/`). 
   - Contoh: Model `Product`, `Category`, dan `StockMovement`.
   - Menangani representasi data, operasi database, dan *relationship* (contoh: relasi `Category hasMany Product` dan `Product belongsTo Category`).
   - Menerapkan `$fillable` untuk mencegah mass-assignment error.
2. **View (V)**: Menggunakan Blade Template Engine (`resources/views/`).
   - Menerapkan Blade Layout (`layouts/app.blade.php`) dan Blade Component (`components/alert.blade.php`) agar *reusable*.
   - Menerapkan directive Blade seperti `@if`, `@forelse`, form dengan `@csrf`, serta menampilkan pesan error validasi (`@error`).
   - Menggunakan Tailwind CSS untuk UI/UX (Tema: Coksu Minimalis).
3. **Controller (C)**: Mengatur logika aplikasi (`app/Http/Controllers/`).
   - Menggunakan **Resource Controller** (`ProductController.php`) untuk menangani operasi CRUD standar (index, create, store, show, edit, update, destroy).
   - Menggunakan `StockController.php` untuk menangani mutasi stok yang dilengkapi dengan `DB::transaction()` dan `lockForUpdate()` persis seperti logika pada P4.

---

## ✨ Fitur Utama (Acceptance Criteria P5)
- **Autentikasi**: Login multi-role (Admin dan Staff) menggunakan Middleware.
- **Manajemen Produk (CRUD)**: Create, Read, Update, Delete data produk dengan relasi kategori.
- **Smart Listing**: Dilengkapi fitur *Search, Filter, Sorting*, dan *Pagination* yang mempertahankan *query string*.
- **Mutasi Stok (IN/OUT)**: Transaksi stok masuk dan keluar dengan validasi ketat (stok tidak boleh minus).
- **Validasi Form**: Validasi input ketat beserta pesan error.

---

## 🚀 Instruksi Instalasi & Menjalankan Proyek
Ikuti urutan *command* berikut untuk menjalankan aplikasi di *environment* lokal (menggunakan Composer dan Artisan):

1. **Clone Repository**
   ```bash
   git clone [URL_GITHUB_REPOSITORY_ANDA]
   cd inventory-laravel