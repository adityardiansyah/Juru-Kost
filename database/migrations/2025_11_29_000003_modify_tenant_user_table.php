<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add role_id column
        Schema::table('tenant_user', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
        });

        // 2. Seed default roles if they don't exist
        $roles = [
            ['name' => 'owner', 'label' => 'Pemilik Kost'],
            ['name' => 'admin', 'label' => 'Admin Kost'],
            ['name' => 'accountant', 'label' => 'Akuntan'],
            ['name' => 'resident', 'label' => 'Penghuni'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore([
                'name' => $role['name'],
                'label' => $role['label'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Migrate existing data
        $existingRoles = DB::table('tenant_user')->select('id', 'role')->get();
        foreach ($existingRoles as $row) {
            $roleId = DB::table('roles')->where('name', $row->role)->value('id');
            if ($roleId) {
                DB::table('tenant_user')->where('id', $row->id)->update(['role_id' => $roleId]);
            }
        }

        // 4. Make role_id required and drop old column
        Schema::table('tenant_user', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable(false)->change();
            $table->dropColumn('role');
        });
    }

    public function down(): void
    {
        Schema::table('tenant_user', function (Blueprint $table) {
            $table->enum('role', ['owner', 'admin', 'accountant', 'resident'])->default('admin')->after('user_id');
        });

        // Restore data (approximate)
        $rows = DB::table('tenant_user')->join('roles', 'tenant_user.role_id', '=', 'roles.id')->select('tenant_user.id', 'roles.name')->get();
        foreach ($rows as $row) {
            // Map back to enum values if possible, default to admin
            $enumRole = in_array($row->name, ['owner', 'admin', 'accountant', 'resident']) ? $row->name : 'admin';
            DB::table('tenant_user')->where('id', $row->id)->update(['role' => $enumRole]);
        }

        Schema::table('tenant_user', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
