



# 📚 SUNGOKONG BOOK - Laravel REST API

SUNGOKONG BOOK adalah RESTful API backend untuk aplikasi perpustakaan digital, dibangun menggunakan **Laravel 11**. API ini mendukung fitur autentikasi, manajemen buku, peminjaman, ulasan buku, serta dashboard khusus untuk admin dan staff.

---

## 🚀 Fitur Utama

### 🔐 Authentication
- `POST /register` - Registrasi user
- `POST /login` - Login dan generate token
- `POST /logout` - Logout dan revoke token
- `GET /me` - Dapatkan informasi user saat ini

### 👤 Profile
- `GET /profile` - Ambil data profil user
- `PUT /profile` - Update profil user

### 📚 Books
- `GET /books` - Lihat semua buku
- `GET /books/top` - Buku paling populer
- `GET /books/category/{category}` - Filter berdasarkan kategori
- `GET /books/{id}` - Detail buku

#### 🔧 Admin Only
- `POST /admin/books` - Tambah buku
- `PUT /admin/books/{id}` - Update buku
- `DELETE /admin/books/{id}` - Hapus buku

### 📖 Loans
- `POST /loans` - Pinjam buku
- `GET /loans/history` - Riwayat peminjaman user
- `PUT /loans/{id}/return` - Kembalikan buku

#### 🔧 Admin / Staff
- `GET /admin/loans` - Lihat semua peminjaman
- `PUT /admin/loans/{id}` - Update status pinjaman
- `GET /staff/loans` - Lihat pinjaman (staff)
- `PUT /staff/loans/{id}` - Update status (staff)

### 🌟 Review
- `GET /books/{bookId}/reviews` - Lihat ulasan buku
- `POST /reviews` - Tambah review (user)

### 📊 Statistik (Admin Only)
- `GET /admin/statistik` - Statistik buku, user, peminjaman, dll

---

## 🛠️ Teknologi yang Digunakan

- Laravel 11 (REST API)
- Sanctum (token-based auth)
- MySQL (database)
- Laravel Feature Test (untuk pengujian otomatis)
- Laravel Seeder & Factory (data dummy)
- Role-based Access Control (admin, staff, user)

---

## ⚙️ Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/username/sungokong-api.git
cd sungokong-api
````

### 2. Install Dependency

```bash
composer install
```
### Install Sanctum
```bash
composer require laravel/sanctum
```

### Publish config jika perlu
```bash
php artisan vendor:publish --tag=sanctum-config
```
### Jalankan migrasi untuk Sanctum
```bash
php artisan migrate
```
### 3. Konfigurasi `.env`

Salin file `.env` dan atur koneksi database:

```bash
cp .env.example .env
```

Sesuaikan `.env`:

```
DB_DATABASE=sungokong
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Key & Migrate

```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Jalankan Server

```bash
php artisan serve
```

---

## 🧪 Testing

Untuk menjalankan pengujian otomatis (Feature Test):

```bash
php artisan test
```

---

## 🗂️ Struktur Folder (API)

```
routes/
  └── api/
      ├── auth.php
      ├── books.php
      ├── loans.php
      ├── reviews.php
      ├── profile.php
      ├── admin/
      │   ├── books.php
      │   ├── loans.php
      │   └── statistik.php
      └── staff/
          └── loans.php

app/
  └── Http/
      └── Controllers/
          ├── AuthController.php
          ├── BookController.php
          ├── LoanController.php
          ├── ReviewController.php
          ├── ProfileController.php
          └── Admin/
              ├── BookController.php
              ├── LoanController.php
              └── StatistikController.php
```

---

## 👥 Role Akses

| Role  | Akses Fitur                        |
| ----- | ---------------------------------- |
| Admin | Semua fitur termasuk statistik     |
| Staff | Peminjaman (lihat dan update)      |
| User  | Login, profil, pinjam, review buku |

---

## 📄 Lisensi

Proyek ini menggunakan lisensi [MIT](LICENSE).

---

## 📬 Kontak

Untuk pertanyaan atau kontribusi:
**Nama:** \[Ahmad Fadhil]
**Email:** \[[ahmadfadhil289@gmail.com](mailto:ahmadfadhil289@gmail.com)]
**GitHub:** [github.com/dhiljaa](https://github.com/dhiljaa)

```
Welcome To Game
```
