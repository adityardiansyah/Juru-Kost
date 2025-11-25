<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('qr_code')->nullable();
            $table->decimal('purchase_price', 12, 2);
            $table->date('purchase_date');
            $table->integer('useful_life_years')->default(5);
            $table->decimal('current_value', 12, 2);
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor', 'broken'])->default('good');
            $table->timestamps();

            $table->index(['tenant_id', 'room_id']);
            $table->index('condition');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
