# MyPOS — Cafe Management System

Web-based Point of Sale (POS) + Inventory Management System untuk cafe, dilengkapi admin panel dan hak akses berbasis role.

🌐 **Live Demo:** [demo-mypos.arifsiddikm.com](https://demo-mypos.arifsiddikm.com)

---

## Tech Stack

- **Backend:** PHP 8.3 + Laravel 12
- **Database:** MySQL / SQLite
- **Frontend:** Tailwind CSS CDN · SweetAlert2 · Blade
- **Font:** Plus Jakarta Sans (Google Fonts)

---

## Fitur

**POS Kasir**
- Transaksi real-time: Dine In & Takeaway
- Denah meja interaktif
- Numpad qty & diskon
- Metode bayar: Cash, Transfer, QRIS
- Hold & cancel order
- Cetak struk

**Admin Panel**
- Dashboard statistik harian + chart 7 hari
- CRUD Menu, Kategori, Bahan Baku, Supplier
- Manajemen Inventory (stok masuk/keluar)
- Data Transaksi History + filter lengkap
- Export laporan PDF & XLSX
- Pengaturan cafe & printer
- Manajemen Users (admin & kasir)

**Role-based Access**
- **Admin** — akses penuh ke semua fitur
- **Kasir** — hanya POS, lihat menu/bahan, data transaksi (read only)

---

## Instalasi

```bash
# 1. Clone repo
git clone https://github.com/arifsiddikm/mypos.git
cd mypos

# 2. Install dependencies
composer install

# 3. Konfigurasi environment
cp file env to .env and setting your password
php artisan key:generate

# 4. Setup database
php artisan migrate
php artisan db:seed

# 5. Storage link
php artisan storage:link

# 6. Jalankan server
php artisan serve
```

Akses di `http://localhost:8000`

---

## Login

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@mypos.com | password |
| Kasir | kasir@mypos.com | password |

---

## Konfigurasi MySQL (opsional)

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mypos
DB_USERNAME=root
DB_PASSWORD=
```

Lalu:
```bash
php artisan migrate
php artisan db:seed
```

---

## Export XLSX (opsional)

Untuk export Excel dengan format `.xlsx` asli:
```bash
composer require phpoffice/phpspreadsheet
```

Tanpa library ini, export otomatis fallback ke CSV (tetap bisa dibuka di Excel).

---

### Support me on
<a href="https://saweria.co/arifsiddikm" target="_blank"><img src="https://user-images.githubusercontent.com/26188697/180601310-e82c63e4-412b-4c36-b7b5-7ba713c80380.png" alt="Sawer me" height="41" width="174"></a>
