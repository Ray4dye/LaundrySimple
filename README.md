# 🧺 LaundrySimple - Sistem Layanan & Pemesanan Laundry

> Proyek Ujian Sertifikasi Kompetensi (Serkom) Rekayasa Perangkat Lunak (RPL)  
> Dibangun menggunakan **Laravel 11 / PHP 8.3**, **Tailwind CSS**, **Laravel Breeze (Blade Stack)**, dan **MySQL**.

---

## 📋 Daftar Isi
1. [Tentang Aplikasi](#-tentang-aplikasi)
2. [Fitur Utama](#-fitur-utama)
3. [Akun Pengguna Default (Seeder)](#-akun-pengguna-default-seeder)
4. [Teori & Kisi-kisi Serkom](#-teori--kisi-kisi-serkom)
   - [Teori 1: Tools & Peran Masing-Masing](#teori-1--tools--peran-masing-masing)
   - [Teori 2: Fungsi ERD & Relasi Database](#teori-2--fungsi-erd--relasi-database)
   - [Teori 3: Perintah Dasar CRUD MySQL](#teori-3--perintah-dasar-crud-mysql)
   - [Teori 4: Pengertian & Manfaat Query SQL](#teori-4--pengertian--manfaat-query-sql)
5. [Struktur Database & ERD](#-struktur-database--erd)
6. [Panduan Instalasi & Menjalankan](#-panduan-instalasi--menjalankan)
7. [Daftar Endpoint / Route](#-daftar-endpoint--route)
8. [Pengujian Otomatis (Testing)](#-pengujian-otomatis-testing)

---

## 🌟 Tentang Aplikasi
**LaundrySimple** adalah aplikasi web manajemen dan pemesanan layanan laundry yang menghubungkan pelanggan (*customer*) dan pengelola (*admin*). Pelanggan dapat melihat katalog layanan (Cuci, Setrika, Paket), menyaring kategori, memilih porsi/satuan, melakukan konfirmasi pesanan dengan modal interaktif, dan mendapatkan nomor antrean. Pengelola laundry (Admin) dapat memantau antrean pesanan secara langsung (*live*), mengubah status pengerjaan (Pending, Selesai, Batal), mengelola master data layanan beserta foto, mengelola akun pengguna, serta mengakses laporan rekap pesanan dan omzet harian.

---

## 🚀 Fitur Utama

### 1. Antarmuka Pelanggan (Customer)
- **Katalog Layanan Interaktif:** Menampilkan semua jenis layanan laundry dengan kategori badge, deskripsi, foto, dan harga.
- **Filter Fleksibel:** Filter kategori cepat (Semua, Cuci, Setrika, Paket) serta filter tanggal/kategori dari sisi server.
- **Formulir Pemesanan Real-time:** Menghitung total harga estimasi secara dinamis saat jumlah porsi diubah.
- **Modal Konfirmasi:** Memastikan pelanggan mengecek kembali nama, nomor antrean, rincian item, dan total tagihan sebelum pesanan dikirim.

### 2. Antarmuka Pengelola (Admin Dashboard)
- **Daftar Pesanan Antrean:** Melihat seluruh pesanan masuk lengkap dengan rincian item layanan, subtotal, dan nomor antrean.
- **Update Status Pesanan:** Memperbarui status pesanan secara instan (`pending`, `completed`/`selesai`, `cancelled`/`batal`).
- **CRUD Layanan Laundry:** Tambah, lihat, ubah (*edit*), dan hapus layanan beserta unggah gambar ke *storage* publik.
- **CRUD Pengguna (User Management):** Pengelolaan akun user dengan pengamanan *role*, validasi email unik, dan *password hashing*.
- **Laporan & Rekapitulasi (Bonus Serkom):**
  - Rekap total porsi tiap layanan yang berhasil diselesaikan berdasarkan rentang tanggal (`SUM(order_details.quantity)`).
  - Rekap jumlah pesanan selesai dan omzet harian (`SUM(orders.total_price)`).
  - Fitur cetak laporan langsung (*Print-ready*).

### 3. Keamanan & Hak Akses (Role-Based Authorization)
- Otentikasi menggunakan Laravel Breeze.
- Menggunakan **Gate Authorization (`can:manage-admin`)** di level server, sehingga user biasa yang mencoba mengakses URL `/admin/*` akan otomatis ditolak dengan kode respon **HTTP 403 Forbidden**.
- Redirect cerdas pasca-login: Admin diarahkan ke **Dashboard**, sedangkan Pelanggan diarahkan ke **Katalog Layanan**.

---

## 👥 Akun Pengguna Default (Seeder)

Setelah menjalankan `php artisan migrate:fresh --seed`, tersedia dua akun bawaan:

| Role | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | `password123` | Dashboard Admin, Kelola Layanan, Kelola User, Laporan Rekap |
| **Customer / User** | `user@gmail.com` | `password123` | Akses Katalog, Checkout Pemesanan, Profil Pribadi |

---

## 📚 Teori & Kisi-kisi Serkom

### Teori 1 · Tools & Peran Masing-Masing
- **VS Code:** Text editor terintegrasi untuk menulis kode sumber, manajemen berkas proyek, dan eksekusi terminal.
- **PHP:** Bahasa pemrograman *backend* yang menjalankan logika bisnis aplikasi Laravel di sisi server.
- **Composer:** *Package dependency manager* untuk ekosistem PHP yang bertugas mengunduh pustaka (seperti Laravel Framework, Breeze, Pint).
- **Laravel:** Kerangka kerja (*framework*) PHP modern berarsitektur MVC (*Model-View-Controller*) yang menyediakan routing, Eloquent ORM, migrasi, validasi, dan autentikasi.
- **MySQL / MariaDB:** *Relational Database Management System* (RDBMS) yang menyimpan seluruh data relasional aplikasi secara terstruktur.
- **Node.js & npm:** Lingkungan runtime JavaScript dan pengelola paket untuk mengompilasi aset frontend menggunakan **Vite** dan **Tailwind CSS**.
- **Git & GitHub:** Sistem pengendali versi (*Version Control System*) untuk mencatat riwayat perubahan berkas dan repositori jarak jauh (*cloud hosting*).

### Teori 2 · Fungsi ERD & Relasi Database
**Entity Relationship Diagram (ERD)** adalah representasi grafis rancangan hubungan antar-entitas database sebelum diimplementasikan ke dalam tabel nyata.
- **Relasi 1 to Many (One-to-Many):**
  - 1 `orders` memiliki banyak `order_details` (`1 : N`)
  - 1 `laundry_items` bisa terdapat di banyak `order_details` (`1 : N`)
- **Tabel Penghubung (`order_details`):** Menghubungkan entitas pesanan dan layanan, mencatat jumlah kuantitas (`quantity`), harga per satuan (`price`), dan `subtotal` per item tanpa menduplikasi data layanan.
- **Primary Key (PK):** Kolom identitas unik untuk tiap baris record (misal: `orders.id`, `laundry_items.id`).
- **Foreign Key (FK):** Kolom yang mengacu pada Primary Key tabel lain untuk menjaga integritas data relasional (`order_details.order_id` & `order_details.laundry_item_id`).

### Teori 3 · Perintah Dasar CRUD MySQL
1. **Create (Tambah Data):**
   ```sql
   INSERT INTO laundry_items (name, category, price, description, created_at, updated_at)
   VALUES ('Cuci Baju Kilat', 'Cuci', 30000, 'Layanan cuci kilat 3 jam', NOW(), NOW());
   ```
2. **Read (Membaca/Menampilkan Data):**
   ```sql
   SELECT id, name, category, price FROM laundry_items WHERE category = 'Cuci';
   ```
3. **Update (Memperbarui Data):**
   ```sql
   UPDATE laundry_items SET price = 32000, updated_at = NOW() WHERE id = 1;
   ```
4. **Delete (Menghapus Data):**
   ```sql
   DELETE FROM laundry_items WHERE id = 1;
   ```
   *(Penting: Klausul `WHERE` wajib disertakan pada UPDATE dan DELETE untuk mencegah seluruh baris tabel terhapus/terubah tanpa sengaja).*

### Teori 4 · Pengertian & Manfaat Query SQL
**Query** adalah instruksi terstruktur yang dikirimkan ke database untuk meminta, mengolah, memfilter, atau memanipulasi data.
- **Manfaat Query:**
  - Mengambil data spesifik tanpa membaca seluruh isi tabel secara manual.
  - Melakukan agregasi statistik seperti menghitung total transaksi (`COUNT`), menghitung akumulasi omzet (`SUM`), mencari nilai rata-rata (`AVG`), atau nilai ekstrem (`MAX`/`MIN`).
  - Menggabungkan beberapa tabel berelasi menggunakan klausul `JOIN`.
  - Mengurutkan (`ORDER BY`) dan mengelompokkan data (`GROUP BY`).

Contoh Query Agregasi Omzet dan Rekap Layanan:
```sql
SELECT f.name, SUM(od.quantity) AS total_dipesan, SUM(od.subtotal) AS total_pendapatan
FROM order_details od
JOIN orders o ON o.id = od.order_id
JOIN laundry_items f ON f.id = od.laundry_item_id
WHERE LOWER(o.status) IN ('completed', 'selesai')
GROUP BY f.id, f.name
ORDER BY total_dipesan DESC;
```

---

## 🗄️ Struktur Database & ERD

```
+------------------+         +---------------------+         +-------------------+
|  laundry_items   |         |    order_details    |         |      orders       |
+------------------+         +---------------------+         +-------------------+
| id (PK)          |<---+    | id (PK)             |    +--->| id (PK)           |
| name             |    +----| laundry_item_id(FK) |    |    | customer_name     |
| category         |         | order_id (FK)       |----+    | queue_number      |
| price            |         | quantity            |         | total_price       |
| description      |         | price               |         | status            |
| image            |         | subtotal            |         | created_at        |
| created_at       |         | created_at          |         | updated_at        |
| updated_at       |         | updated_at          |         +-------------------+
+------------------+         +---------------------+

+------------------+
|      users       |
+------------------+
| id (PK)          |
| name             |
| email (Unique)   |
| password         |
| role             | -> ('admin' / 'user')
| created_at       |
| updated_at       |
+------------------+
```

---

## 🛠️ Panduan Instalasi & Menjalankan

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal:

### 1. Clone Repositori
```bash
git clone https://github.com/Ray4dye/LaundrySimple.git
cd LaundrySimple
```

### 2. Instal Dependensi PHP & Frontend
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan (.env)
Salin berkas `.env.example` menjadi `.env`:
```bash
cp .env.example .env
php artisan key:generate
```
Pastikan pengaturan database di `.env` sesuai dengan konfigurasi lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306 # atau 3307 jika menggunakan port khusus
DB_DATABASE=laundry
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Jalankan Migrasi & Seeder
Buat database `laundry` di MySQL terlebih dahulu, lalu jalankan:
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 5. Kompilasi Aset Frontend
```bash
npm run build
# atau untuk mode pengembangan:
npm run dev
```

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Buka browser dan akses aplikasi di: `http://127.0.0.1:8000`

---

## 🧭 Daftar Endpoint / Route

| Method | URI | Route Name | Keterangan & Hak Akses |
| :--- | :--- | :--- | :--- |
| `GET` | `/` | `customer.index` | Halaman katalog layanan pelanggan (Publik) |
| `POST` | `/checkout` | `customer.checkout` | Proses pesanan pelanggan (Publik) |
| `GET` | `/login` | `login` | Form login akun |
| `GET` | `/register` | `register` | Form pendaftaran user baru |
| `GET` | `/dashboard` | `dashboard` | Dashboard admin (Auth & `can:manage-admin`) |
| `PATCH` | `/admin/orders/{id}/status` | `admin.orders.updateStatus` | Ubah status antrean pesanan |
| `GET` | `/admin/laundry_items` | `laundry_items.index` | Master data layanan laundry (CRUD) |
| `GET` | `/admin/users` | `admin.users.index` | Master data pengguna (CRUD User) |
| `GET` | `/admin/reports/layanans` | `admin.reports.layanans` | Laporan rekapitulasi & omzet harian |

---

## 🧪 Pengujian Otomatis (Testing)

Proyek ini telah dilengkapi pengujian otomatis Feature Test dan Unit Test menggunakan **PHPUnit**:
```bash
php artisan test
```
Semua skenario pengujian autentikasi, otorisasi role admin/user, validasi form, kalkulasi checkout, dan laporan telah teruji **100% PASSING**.

---

<p align="center">
  Dibuat dengan ❤️ untuk Ujian Sertifikasi Kompetensi (Serkom) Rekayasa Perangkat Lunak.
</p>
