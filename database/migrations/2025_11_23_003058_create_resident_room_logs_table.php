<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resident_room_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('monthly_price', 12, 2);
            $table->timestamps();

            $table->index(['tenant_id', 'resident_id']);
            $table->index(['tenant_id', 'room_id']);
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resident_room_logs');
    }
};
