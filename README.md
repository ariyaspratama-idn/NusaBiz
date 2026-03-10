# 🌊 NusaBiz by Wave Project

Platform Bisnis Terpadu Indonesia — **Profil Perusahaan + Toko Online + ERP Internal** dalam satu ekosistem Laravel.

## ✨ Fitur Utama
- 🛍️ **E-Commerce Mandiri** — Katalog produk, varian, checkout guest
- 📦 **Manajemen Pesanan** — Status order, verifikasi pembayaran, resi otomatis
- 📝 **CMS Profil Perusahaan** — Artikel, blog, testimoni, portofolio
- 💬 **Live Chat Internal** — Customer service langsung di website (tanpa sosmed)
- 💰 **ERP Keuangan** — Buku besar, transaksi, laporan laba rugi (dari financial-app)
- 🔧 **Modul Bengkel** — Booking, work order, riwayat servis (dari bengkel-motor)
- 🏢 **Multi-Cabang** — Multi-branch management
- 🌐 **Multi-Bahasa** — Bahasa Indonesia & English

## 🚀 Cara Menjalankan (Local)

### Prasyarat
- PHP 8.2+
- Composer
- Node.js 18+
- Laragon atau XAMPP

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Setup database (isi .env dulu dengan koneksi MySQL/TiDB)
php artisan migrate --force
php artisan db:seed

# 4. Link storage publik
php artisan storage:link

# 5. Jalankan server
php artisan serve
npm run dev
```

## ☁️ Deployment (Vercel + TiDB Cloud)

1. Push ke GitHub: `git push origin main`
2. Import repo di [vercel.com](https://vercel.com)
3. Set Environment Variables di Vercel dashboard
4. Deploy!

## 🔑 Environment Variables Penting

| Variable | Deskripsi |
|---|---|
| `DB_HOST` | Host TiDB Cloud |
| `MIDTRANS_SERVER_KEY` | API Key Midtrans |
| `FONNTE_TOKEN` | Token WhatsApp Fonnte |
| `RAJAONGKIR_API_KEY` | API Key RajaOngkir |

## 👥 Role Pengguna

| Role | Akses |
|---|---|
| `SUPER_ADMIN` | Akses penuh semua fitur |
| `ADMIN_OPERASIONAL` | Kelola pesanan & servis |
| `EDITOR_KONTEN` | Kelola artikel & CMS |
| `CASHIER` | Input transaksi |
| `AUDITOR` | Lihat laporan |
| `CUSTOMER` | Belanja di storefront |

---
Made with ❤️ by **Wave Project** · [GitHub](https://github.com/ariyaspratama-idn)
