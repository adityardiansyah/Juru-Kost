<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_type_id')->nullable()->constrained()->onDelete('set null');
            $table->string('room_number');
            $table->decimal('price', 12, 2);
            $table->text('facilities')->nullable();
            $table->json('photos')->nullable();
            $table->enum('status', ['available', 'occupied', 'booked', 'maintenance'])->default('available');
            $table->timestamps();

            $table->unique(['tenant_id', 'room_number']);
            $table->index('tenant_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
