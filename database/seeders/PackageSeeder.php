<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lifetime Package - Limited to 100 tenants
        Package::create([
            'name' => 'Akses Lifetime',
            'slug' => 'lifetime-access',
            'description' => 'Satu kali bayar, akses selamanya! Kelola kost tanpa batas waktu.',
            'price' => 299000,
            'original_price' => 2499000,
            'discount_percentage' => 88,
            'type' => 'lifetime',
            'is_active' => true,
            'max_tenants' => 100, // Will auto-disable after 100 tenants
            'current_tenants' => 0,
            'features' => [
                'Manajemen Penghuni Unlimited',
                'Billing & Tagihan Otomatis',
                'Laporan Keuangan Real-time',
                'Manajemen Kamar & Aset',
                'Multi-Tenant (Kelola Banyak Kost)',
            ],
            'bonus_features' => [
                'Update Fitur Selamanya',
                'Priority Support 24/7',
                'Template Dokumen Lengkap',
                'Video Tutorial Lengkap',
                'Konsultasi Setup Gratis',
            ],
            'sort_order' => 1,
        ]);

        // Monthly Package - For future use
        Package::create([
            'name' => 'Paket Bulanan',
            'slug' => 'monthly-subscription',
            'description' => 'Fleksibel dengan pembayaran bulanan. Batal kapan saja.',
            'price' => 99000,
            'original_price' => 199000,
            'discount_percentage' => 50,
            'type' => 'monthly',
            'is_active' => false, // Not active yet
            'max_tenants' => null, // Unlimited
            'current_tenants' => 0,
            'features' => [
                'Manajemen Penghuni Unlimited',
                'Billing & Tagihan Otomatis',
                'Laporan Keuangan Real-time',
                'Manajemen Kamar & Aset',
                'Multi-Tenant (Kelola Banyak Kost)',
            ],
            'bonus_features' => [
                'Update Fitur Bulanan',
                'Email Support',
            ],
            'sort_order' => 2,
        ]);

        // Yearly Package - For future use
        Package::create([
            'name' => 'Paket Tahunan',
            'slug' => 'yearly-subscription',
            'description' => 'Hemat lebih banyak dengan paket tahunan. Gratis 2 bulan!',
            'price' => 999000,
            'original_price' => 1999000,
            'discount_percentage' => 50,
            'type' => 'yearly',
            'is_active' => false, // Not active yet
            'max_tenants' => null, // Unlimited
            'current_tenants' => 0,
            'features' => [
                'Manajemen Penghuni Unlimited',
                'Billing & Tagihan Otomatis',
                'Laporan Keuangan Real-time',
                'Manajemen Kamar & Aset',
                'Multi-Tenant (Kelola Banyak Kost)',
            ],
            'bonus_features' => [
                'Update Fitur Selamanya',
                'Priority Support 24/7',
                'Template Dokumen Lengkap',
                'Gratis 2 Bulan',
            ],
            'sort_order' => 3,
        ]);
    }
}
