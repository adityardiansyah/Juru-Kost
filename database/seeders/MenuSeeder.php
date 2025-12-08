<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Role;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Create main menus
        $dashboard = Menu::create([
            'name' => 'Dashboard',
            'route_name' => 'dashboard',
            'icon_svg' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'order' => 1,
        ]);

        $kamar = Menu::create([
            'name' => 'Kamar',
            'route_name' => null,
            'icon_svg' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
            'order' => 2,
        ]);

        Menu::create([
            'name' => 'Data Kamar',
            'route_name' => 'rooms.index',
            'parent_id' => $kamar->id,
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Tipe Kamar',
            'route_name' => 'room-types.index',
            'parent_id' => $kamar->id,
            'order' => 2,
        ]);

        $penghuni = Menu::create([
            'name' => 'Penghuni',
            'route_name' => 'residents.index',
            'icon_svg' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            'order' => 3,
        ]);

        $tagihan = Menu::create([
            'name' => 'Tagihan',
            'route_name' => 'bills.index',
            'icon_svg' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'order' => 4,
        ]);

        $keuangan = Menu::create([
            'name' => 'Keuangan',
            'route_name' => null,
            'icon_svg' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'order' => 5,
        ]);

        Menu::create([
            'name' => 'Laporan Keuangan',
            'route_name' => 'finance.index',
            'parent_id' => $keuangan->id,
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Pendapatan',
            'route_name' => 'finance.incomes.index',
            'parent_id' => $keuangan->id,
            'order' => 2,
        ]);

        Menu::create([
            'name' => 'Pengeluaran',
            'route_name' => 'finance.expenses.index',
            'parent_id' => $keuangan->id,
            'order' => 3,
        ]);

        Menu::create([
            'name' => 'Hutang',
            'route_name' => 'finance.debts.index',
            'parent_id' => $keuangan->id,
            'order' => 4,
        ]);

        Menu::create([
            'name' => 'Piutang',
            'route_name' => 'finance.debts.index',
            'parent_id' => $keuangan->id,
            'order' => 5,
        ]);

        Menu::create([
            'name' => 'Kategori Pemasukan',
            'route_name' => 'finance.income-categories.index',
            'parent_id' => $keuangan->id,
            'order' => 4,
        ]);

        Menu::create([
            'name' => 'Kategori Pengeluaran',
            'route_name' => 'finance.expense-categories.index',
            'parent_id' => $keuangan->id,
            'order' => 5,
        ]);

        $aset = Menu::create([
            'name' => 'Aset & Inventori',
            'route_name' => null,
            'icon_svg' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            'order' => 6,
        ]);

        Menu::create([
            'name' => 'Aset',
            'route_name' => 'assets.index',
            'parent_id' => $aset->id,
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Inventori',
            'route_name' => 'inventories.index',
            'parent_id' => $aset->id,
            'order' => 2,
        ]);

        // Superuser only menus
        $userRole = Menu::create([
            'name' => 'User & Role',
            'route_name' => null,
            'icon_svg' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
            'order' => 7,
            'requires_superuser' => true,
        ]);

        Menu::create([
            'name' => 'User Management',
            'route_name' => 'admin.users.index',
            'parent_id' => $userRole->id,
            'order' => 1,
            'requires_superuser' => true,
        ]);

        Menu::create([
            'name' => 'Role Management',
            'route_name' => 'admin.roles.index',
            'parent_id' => $userRole->id,
            'order' => 2,
            'requires_superuser' => true,
        ]);

        Menu::create([
            'name' => 'Menu Management',
            'route_name' => 'admin.menus.index',
            'parent_id' => $userRole->id,
            'order' => 3,
            'requires_superuser' => true,
        ]);

        // Assign all non-superuser menus to Owner and Admin roles
        $ownerRole = Role::where('name', 'owner')->first();
        $adminRole = Role::where('name', 'admin')->first();

        if ($ownerRole || $adminRole) {
            $regularMenus = Menu::where('requires_superuser', false)->get();

            if ($ownerRole) {
                $ownerRole->menus()->attach($regularMenus->pluck('id'));
            }

            if ($adminRole) {
                $adminRole->menus()->attach($regularMenus->pluck('id'));
            }
        }
    }
}
