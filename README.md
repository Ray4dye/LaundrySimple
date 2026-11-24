# 🧺 LaundrySimple — Sistem Operasional & Rekap Transaksi Laundry Modern

Aplikasi web manajemen operasional laundry terintegrasi yang menghubungkan kasir, administrator, dan pelanggan dengan antarmuka presisi, responsif, dan mudah digunakan.

---

## 📋 Daftar Fitur Utama

### 1. Antarmuka Pelanggan & Pemesanan
- **Katalog Layanan Interaktif:** Menampilkan seluruh jenis layanan laundry dengan kategori badge, deskripsi, foto/ikon, dan harga per kilogram/satuan.
- **Filter Kategori Cepat:** Penyaringan instan berdasarkan kategori pengerjaan (Semua, Cuci Komplit, Cuci Lipat, Cuci Kilat, dll).
- **Formulir Pemesanan Real-time:** Menghitung total harga estimasi secara dinamis saat jumlah berat atau kuantitas diubah.
- **Modal Konfirmasi & Cetak Nota:** Memastikan pengecekan rincian pesanan dan nomor antrean sebelum pesanan diproses, serta dukungan cetak nota transaksi.

### 2. Antarmuka Dashboard Kasir & Admin
- **Rekap Transaksi Masuk:** Memantau antrean pesanan masuk lengkap dengan rincian layanan, subtotal, dan status.
- **Pembaruan Status Transaksi:** Mengubah status cucian langsung pada tabel (`proses`, `selesai`, `batal`).
- **Master Data Layanan:** Pengelolaan paket cucian, kategori pengerjaan, dan tarif harga.
- **Data Pelanggan (Customer Directory):** Rekapitulasi data pelanggan, riwayat belanja, dan tautan langsung WhatsApp.
- **Visualisasi & Analitik:** Grafik mingguan dan diagram donat proporsi status pesanan yang interaktif dan responsif.

### 3. Sistem Hak Akses (Role-Based Access Control)
- **Administrator:** Akses penuh ke seluruh fitur sistem dan master data layanan.
- **Kasir:** Fokus pada pencatatan transaksi masuk, update status harian, dan cetak struk pembayaran.

---

## 👥 Akun Pengguna Default

| Peran | Email | Kata Sandi | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@gmail.com` | `admin123` | Dashboard Admin, Kelola Layanan, Kelola User, Laporan Rekap |
| **Kasir** | `kasir@gmail.com` | `kasir123` | Operasional Kasir, Tambah Transaksi, Rekap, Cetak Struk |

---

## 🛠️ Kebutuhan Sistem & Instalasi

### Kebutuhan:
- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL / MariaDB

### Langkah Instalasi:
```bash
# 1. Clone repositori
git clone https://github.com/Ray4dye/LaundrySimple.git
cd LaundrySimple

# 2. Instal dependensi PHP & JavaScript
composer install
npm install

# 3. Konfigurasi Environment
cp .env.example .env
php artisan key:generate

# 4. Migrasi & Seeder Database
php artisan migrate:fresh --seed

# 5. Build Aset Frontend
npm run build

# 6. Jalankan Server Lokal
php artisan serve
```

---

## 🌐 Pratinjau Langsung (GitHub Pages)

Aplikasi ini dapat diakses secara langsung melalui antarmuka web statis interaktif pada:  
🔗 **[https://ray4dye.github.io/LaundrySimple/](https://ray4dye.github.io/LaundrySimple/)**

---

## 📄 Lisensi

Aplikasi ini dilisensikan di bawah [MIT License](LICENSE).
