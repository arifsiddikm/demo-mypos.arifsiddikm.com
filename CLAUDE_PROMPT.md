# 🤖 CLAUDE PROMPT — MyPOS (Cafe Management System)

> Upload file ini ke Claude lalu ketik: **"Bantu saya build project ini dari awal"**
> Claude akan membuatkan seluruh project MyPOS sesuai spesifikasi di bawah.

---

## 📋 PROJECT OVERVIEW

**Nama Project:** MyPOS  
**Konsep:** Web-based Point of Sale (POS) + Inventory Management System untuk cafe/resto  
**Stack:** Laravel 12 · PHP 8.3 · MySQL · Tailwind CSS (CDN) · Blade · SweetAlert2  
**Font:** Plus Jakarta Sans (Google Fonts)  
**Warna Tema:** Coffee brown — primary `#5c3d1e`, background `#fdf8f0`, accent `#f5deb3`

---

## 🏗️ STRUKTUR DATABASE

### Tabel `users`
```
id, name, email, password, role (enum: admin|kasir), is_active (bool), timestamps
```

### Tabel `categories`
```
id, name, slug, icon (emoji), sort_order, timestamps
```

### Tabel `menus`
```
id, category_id (FK), name, description, price, image (URL atau path lokal), is_available (bool), sort_order, timestamps
```

### Tabel `tables`
```
id, name, capacity, status (enum: available|occupied), pos_x, pos_y, timestamps
```

### Tabel `suppliers`
```
id, name, contact_person, phone, email, address, is_active (bool), timestamps
```

### Tabel `ingredients`
```
id, supplier_id (FK nullable), name, unit, stock (decimal), min_stock (decimal), cost_per_unit (decimal), timestamps
```

### Tabel `inventory_movements`
```
id, ingredient_id (FK), supplier_id (FK nullable), user_id (FK), type (enum: in|out|adjustment), quantity (decimal), cost_per_unit (decimal nullable), notes, movement_date, timestamps
```

### Tabel `menu_ingredients` (pivot)
```
id, menu_id (FK), ingredient_id (FK), quantity_used (decimal), timestamps
```

### Tabel `transactions`
```
id, invoice_number (unique), user_id (FK), table_id (FK nullable), order_type (enum: dine_in|takeaway), status (enum: open|hold|paid|cancelled), payment_method (enum: cash|transfer|qris nullable), subtotal, tax, discount, total, paid_amount, change_amount, notes, paid_at (nullable), timestamps
```

### Tabel `transaction_items`
```
id, transaction_id (FK), menu_id (FK nullable), menu_name, price, quantity, subtotal, notes, timestamps
```

### Tabel `settings`
```
id, key (unique), value, timestamps
```
**Keys:** `cafe_name`, `cafe_address`, `cafe_phone`, `cafe_email`, `tax_percentage`, `cafe_description`, `cafe_tagline`

### Tabel `printer_settings`
```
id, printer_name, printer_type, auto_print (bool), paper_size, header_text, footer_text, timestamps
```

---

## 👥 ROLE & HAK AKSES

### Admin — akses penuh ke semua halaman:
- Dashboard, POS Kasir, Data Transaksi
- CRUD Menu, Kategori, Bahan, Inventory, Supplier
- Manajemen Users
- Laporan (transaksi + inventory)
- Pengaturan (cafe + printer)

### Kasir — akses terbatas:
- ✅ Dashboard (read only)
- ✅ POS Kasir (full akses — ini halaman utama kasir)
- ✅ Data Transaksi (read only, bisa export)
- ✅ Data Menu (read only — tidak ada tombol tambah/edit/hapus)
- ✅ Data Bahan (read only)
- ✅ Inventory (read only)
- ❌ CRUD apapun (menu, bahan, supplier, kategori, inventory input)
- ❌ Users, Pengaturan, Laporan Inventory

**Middleware:** Buat `AdminOnly` middleware, register alias `admin.only` di `bootstrap/app.php`.  
**Kasir login** → langsung redirect ke `/pos` (hapus `session('url.intended')` sebelum redirect).  
**Admin login** → redirect ke `/admin/dashboard` via `redirect()->intended()`.

---

## 🎨 LAYOUT & UI

### Layout Admin (`layouts/admin.blade.php`)
- Sidebar kiri lebar 240px, warna putih, border kanan cream
- Logo MyPOS di atas sidebar
- Nav item dengan icon SVG, aktif state background coffee brown
- **Role-based sidebar:** item yang tidak diizinkan kasir disembunyikan dengan `@if($isAdmin)`
- Item Menu & Bahan tampil untuk kasir tapi ada label badge kecil "View"
- User info + tombol logout di bawah sidebar
- **Banner biru** di bawah topbar kalau login sebagai kasir: "Mode Kasir — Beberapa fitur dibatasi"
- Topbar: judul halaman + clock realtime
- Alert session (`success` / `error`) auto-dismiss 4 detik
- Konfirmasi logout pakai SweetAlert2
- Global CSS class: `.btn`, `.btn-primary`, `.btn-secondary`, `.btn-danger`, `.badge`, `.card`, `.form-input`, `.data-table`

---

## 📄 HALAMAN-HALAMAN

### 1. Landing Page (`/`)
- Navbar fixed: logo, nav links (Home, Tentang, Menu, Kontak), tombol Login Admin
- Hero section: gradient coffee brown, tagline besar, 2 CTA button
- About section: deskripsi cafe + 3 feature card
- **Menu section:** grid 4 kolom, filter kategori (tombol pill), setiap card punya:
  - Image dengan smart resolver: cek `http/https` → URL langsung, string lain → `/storage/`, kosong → emoji kategori
  - Skeleton shimmer saat loading
  - `onerror` fallback ke emoji
  - Nama, kategori, deskripsi (limit 65 char), harga
  - Badge "Habis" kalau `is_available = false`
- Contact section: dark coffee background, 3 info card (lokasi, telp, email)
- Footer
- Filter kategori: JS `filterMenu(slug)` dengan animasi fade

### 2. Login Page (`/login`)
- Split layout: panel kiri dekoratif (coffee gradient, feature list, floating emoji), form kanan
- Fields: email, password (toggle show/hide), remember me
- **2 tombol Quick Login (Testing):**
  - 👑 Admin — autofill `admin@mypos.com` / `password`
  - 🧑‍💼 Kasir — autofill `kasir@mypos.com` / `password`
- Error alert merah di atas form
- Link kembali ke landing

### 3. Dashboard (`/admin/dashboard`)
- 4 stat card hari ini: Total Penjualan, Jumlah Transaksi, Open Order, Low Stock
- Chart penjualan 7 hari (bisa pakai Chart.js atau SVG sederhana)
- Tabel Top 5 Menu terlaris

### 4. POS Kasir (`/pos`)
- Layout 3 kolom penuh layar (tanpa navbar/sidebar admin):
  - **Kiri (278px):** Cart panel
    - Tab Dine In / Takeaway
    - Indikator meja aktif
    - List item pesanan: nama, harga satuan, qty control (+/-), subtotal, tombol hapus
    - Totals: subtotal, pajak, diskon (input angka)
    - Tombol Checkout
  - **Tengah:** Menu panel
    - Search bar
    - Filter kategori (horizontal scroll)
    - Grid menu card — image dengan **smart resolver** (sama seperti landing):
      ```js
      function resolveImg(image) {
          if (!image) return null;
          if (image.startsWith('http://') || image.startsWith('https://')) return image;
          return '/storage/' + image;
      }
      ```
    - Setiap card: skeleton shimmer, img, onerror fallback emoji, nama, harga
  - **Kanan (300px):** Numpad
    - Context item aktif
    - Mode tab: Qty / Diskon
    - Display angka besar
    - Grid numpad 3×4 + tombol 000, backspace, CLEAR, OK
    - Action buttons: Dine In, Takeaway, Meja, Hold, Batal, Checkout
- **Topbar tipis:** Logo, clock, nama kasir, invoice pill (muncul kalau ada transaksi aktif), tombol kembali ke dashboard
- **Modal Meja:** denah cafe dengan `.table-node` positioned absolute, warna hijau=kosong, oranye=terisi
- **Modal Checkout:** pilih metode bayar (3 tombol), input nominal, quick amount (+10K/20K/50K/100K/Pas), display kembalian, notes, tombol proses
- **Modal Struk:** detail transaksi setelah bayar, tombol Print dan Baru
- **Flow transaksi:** `start` → `addItem` → `updateItem/removeItem` → `hold/cancel/checkout` → struk
- Semua action pakai `fetch()` API ke backend, CSRF header disertakan

### 5. Data Menu (`/admin/menus`)
- Table: thumbnail (smart image), nama, kategori badge, harga, status badge
- Search realtime (JS filter)
- Admin: tombol Tambah, Edit, Hapus (konfirmasi SweetAlert)
- Kasir: hanya tabel, ada banner "View Only"
- Form tambah/edit: nama, kategori (select), harga, deskripsi, upload gambar (file atau URL), toggle is_available

### 6. Data Bahan (`/admin/ingredients`)
- Table: nama, satuan, supplier, stok (merah kalau low), min stok, harga/unit, status badge
- Admin: CRUD lengkap
- Kasir: view only

### 7. Inventory (`/admin/inventory`)
- Table: tanggal, bahan, tipe (in/out/adjustment) badge, qty, supplier, user, notes
- Admin: form input movement (pilih bahan, tipe, qty, supplier, tanggal, notes)
- Kasir: view only

### 8. Supplier (`/admin/suppliers`) — Admin only
- CRUD: nama, contact person, phone, email, alamat, toggle aktif

### 9. Users (`/admin/users`) — Admin only
- CRUD: nama, email, password, role (admin/kasir), toggle aktif

### 10. Data Transaksi (`/admin/transactions`)
- **Ini halaman baru** (beda dari laporan)
- **7 filter:** dari-sampai tanggal, status, kasir (dropdown), metode bayar, tipe order, search invoice
- **4 stat card:** total lunas, total pendapatan, dibatalkan, rata-rata per transaksi
- Table: invoice, tanggal, kasir, meja, tipe badge, metode badge, jumlah item, total, status badge, tombol Detail
- Pagination (20 per halaman)
- **Export PDF** (buka tab baru, auto print) dan **Export XLSX**
  - XLSX: pakai `phpoffice/phpspreadsheet` kalau terinstall, fallback CSV dengan BOM UTF-8
- Halaman detail transaksi: info lengkap + tabel item + summary pembayaran

### 11. Laporan (`/admin/reports`) — Admin only
- Sub-halaman: Laporan Transaksi, Laporan Inventory
- Filter tanggal + status
- Export PDF + Excel

### 12. Pengaturan (`/admin/settings`) — Admin only
- Tab Cafe: nama, alamat, telepon, email, pajak (%), tagline, deskripsi
- Tab Printer: nama printer, tipe, auto print, ukuran kertas, header/footer text

---

## 🌱 SEEDER (DatabaseSeeder.php)

### Users (5):
- `admin@mypos.com` / `password` / role: admin
- `kasir@mypos.com` / `password` / role: kasir
- `kasir2@mypos.com`, `kasir3@mypos.com` / role: kasir
- `kasir4@mypos.com` / role: kasir, is_active: false

### Categories (7):
Semua, Minuman, Makanan, Snack, Dessert, Kopi, Jus & Smoothie

### Menus (55+):
Setiap menu **wajib** punya `image` berupa URL Unsplash lengkap:
```
https://images.unsplash.com/photo-{ID}?w=400&h=300&fit=crop&auto=format
```
Contoh distribusi:
- Kopi: 15 item (Espresso, Americano, Cappuccino, Latte, Cold Brew, dll)
- Minuman: 11 item (Matcha, Thai Tea, Taro, Lemonade, dll)
- Jus & Smoothie: 6 item
- Makanan: 12 item (Nasi Goreng, Pasta, Burger, Sandwich, dll)
- Snack: 8 item (French Fries, Croissant, Chicken Wings, dll)
- Dessert: 8 item (Cheesecake, Tiramisu, Waffle, dll)

### Suppliers (5):
PT. Kopi Nusantara, UD. Sari Rasa, CV. Dapur Sehat, PT. Fresh Farm Indonesia, Toko Bahan Kue Bu Tini

### Ingredients (25):
Biji kopi, susu, matcha, coklat, tepung, telur, ayam, beras, buah-buahan, dll — masing-masing dengan supplier, stok, min_stock, cost_per_unit

### Inventory Movements (12):
Simulasi pembelian + pemakaian 2 bulan terakhir

### Tables (15):
Meja 1-10, Bar 1-2, VIP 1-2, Outdoor — dengan `pos_x`, `pos_y` untuk denah

### Transactions (80+):
Loop 60 hari ke belakang, 1-7 transaksi per hari, random:
- Kasir acak dari pool kasir aktif
- Menu acak 1-5 item, qty 1-3
- Payment method random (cash lebih sering)
- 10% kemungkinan cancelled
- Status paid: hitung `paid_amount` (dibulatkan ke atas ribuan), `change_amount`

### Settings:
Isi semua key settings dengan data cafe default MyPOS.

---

## 🔧 ROUTES STRUCTURE

```
GET  /                          → landing
GET  /login                     → auth.login
POST /login                     → auth.authenticate
POST /logout                    → auth.logout

// Semua role (auth middleware)
GET  /admin/dashboard
GET  /admin/menus               → index
GET  /admin/menus/{id}          → show
GET  /admin/ingredients         → index
GET  /admin/inventory           → index
GET  /admin/transactions        → index (halaman baru)
GET  /admin/transactions/{id}   → show
GET  /admin/transactions/export/pdf
GET  /admin/transactions/export/excel
GET  /admin/reports/transactions
GET  /admin/reports/transactions/export/*
GET  /pos, /pos/tables, /pos/menus
POST /pos/transaction/start
GET|POST|PUT|DELETE /pos/transaction/{id}/*

// Admin only (admin.only middleware)
CRUD /admin/menus (create/store/edit/update/destroy)
CRUD /admin/categories
CRUD /admin/ingredients (create/store/edit/update/destroy)
POST /admin/inventory (store)
CRUD /admin/suppliers
CRUD /admin/users
GET  /admin/reports/inventory + exports
GET|POST /admin/settings
GET|POST /admin/settings/printer
```

---

## ⚙️ CARA INSTALASI

```bash
composer install
cp .env.example .env
php artisan key:generate
# setup DB di .env
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
```

**Optional XLSX:**
```bash
composer require phpoffice/phpspreadsheet
```

---

## 📝 CATATAN PENTING UNTUK CLAUDE

1. **Smart image resolver** harus ada di SEMUA tempat yang render gambar menu:
   - Landing page (Blade)
   - Admin menu index (Blade)
   - POS kasir grid (JavaScript `resolveImg()` function)

2. **Kasir redirect:** Di `AuthController@login`, setelah `Auth::attempt()` berhasil:
   ```php
   if ($user->role === 'kasir') {
       $request->session()->forget('url.intended');
       return redirect()->route('pos.index');
   }
   return redirect()->intended(route('admin.dashboard'));
   ```

3. **AdminOnly middleware** redirect kasir ke `pos.index` (bukan `admin.dashboard`)

4. **Sidebar** sembunyikan menu admin-only pakai `@if(auth()->user()->role === 'admin')` — jangan pakai middleware check di sini

5. **POS** tidak pakai layout admin — full-page standalone dengan topbar sendiri

6. **Export PDF** cukup render HTML view dengan `window.print()` — tidak perlu library PDF

7. **Semua konfirmasi delete** pakai SweetAlert2 via `data-confirm` attribute + global JS handler

8. **Invoice number format:** `INV-YYYYMMDD-0001`
