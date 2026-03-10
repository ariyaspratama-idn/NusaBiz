<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sops = [
            // 1. Frontliner
            ['category' => 'Frontliner', 'name' => 'Aturan Greeting 3 Detik', 'description' => 'Kontak mata dan senyum dalam 3 detik pertama. Salam standar: "Selamat datang di..."'],
            ['category' => 'Frontliner', 'name' => 'Penanganan Komplain (LAER)', 'description' => 'Listen, Acknowledge, Explore, Respond. Jangan menyela atau menyalahkan tim lain.'],
            
            // 2. Transaksi & Kasir
            ['category' => 'Transaksi', 'name' => 'Konfirmasi & Nominal Kasir', 'description' => 'Bacakan ulang pesanan dan sebutkan nominal total sebelum transaksi.'],
            ['category' => 'Transaksi', 'name' => 'Penerimaan & Struk Kasir', 'description' => 'Hitung uang di depan pelanggan. Serahkan struk dengan dua tangan.'],
            
            // 3. Administrasi
            ['category' => 'Administrasi', 'name' => 'Respon Komunikasi (15m/24h)', 'description' => 'Balas WA max 15 menit. Email max 24 jam.'],
            ['category' => 'Administrasi', 'name' => 'Format Penamaan File Digital', 'description' => '[YYYY-MM-DD]_[JENIS]_[NAMA]. Contoh: 2026-02-15_Invoice_ClientA.pdf'],
            ['category' => 'Administrasi', 'name' => 'Clean Desk Policy', 'description' => 'Meja bersih dari dokumen rahasia sebelum pulang. PC Shut Down.'],
            
            // 4. Kebersihan & Grooming
            ['category' => 'Grooming', 'name' => 'Standar Atribut & Rambut', 'description' => 'Seragam setrika, nametag terpasang. Rambut rapi/ikat.'],
            ['category' => 'Kebersihan', 'name' => 'Toilet Checklist (Hourly)', 'description' => 'Cek toilet setiap 1 jam (Tisu, sabun, lantai kering, bau).'],
            ['category' => 'Kebersihan', 'name' => 'Cleaning Area (2x Daily)', 'description' => 'Sapu dan pel lobby/toko pagi sebelum buka & siang saat sepi.'],
            
            // 5. Operasional Harian
            ['category' => 'Operasional', 'name' => 'Opening Prosedur (30m)', 'description' => 'Lampu, AC, Musik On. Cek stok display. Briefing pagi 5-10 menit.'],
            ['category' => 'Operasional', 'name' => 'Closing Prosedur', 'description' => 'Settlement kasir (fisik vs sistem). Buang sampah basah. Kunci & foto GWA.'],
            
            // 6. Safety & Handover
            ['category' => 'Safety', 'name' => 'Barang Tertinggal (Lost & Found)', 'description' => 'Plastik bening, catat waktu, simpan di laci Lost & Found.'],
            ['category' => 'Operasional', 'name' => 'Serah Terima Shift (Handover)', 'description' => 'Lapor masalah per hari, stok habis, dan pesanan titipan ke shift berikutnya.'],
            
            // 9. Inventory Control
            ['category' => 'Inventory', 'name' => 'Receiving Barang (Visual Check)', 'description' => 'Cek Fisik vs Surat Jalan. Reject di tempat jika rusak/layu.'],
            ['category' => 'Inventory', 'name' => 'Waste Management (Pencatatan)', 'description' => 'Barang rusak dilarang langsung buang. Wajib lapor Manager & catat Form Waste.'],
            
            // 12. Finance
            ['category' => 'Finance', 'name' => 'Petty Cash & Reimburse (Nota)', 'description' => 'Wajib sertakan struk/nota asli untuk penggantian uang operasional.'],
        ];

        $branches = \App\Models\Branch::all();

        foreach ($sops as $data) {
            $sop = \App\Models\Sop::updateOrCreate(['name' => $data['name']], $data);
            
            // Assign to all branches by default
            $sop->branches()->sync($branches->pluck('id'));
        }
    }
}
