<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Insert expense category for debt payments if it doesn't exist
        DB::table('expense_categories')->insertOrIgnore([
            [
                'name' => 'Pembayaran Hutang',
                'description' => 'Kategori untuk pembayaran cicilan hutang/pinjaman',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down(): void
    {
        // Remove the category if needed
        DB::table('expense_categories')
            ->where('name', 'Pembayaran Hutang')
            ->delete();
    }
};
