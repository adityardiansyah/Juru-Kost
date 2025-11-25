<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('income_category_id')->constrained()->onDelete('cascade');
            $table->date('transaction_date');
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->string('proof_file')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'transaction_date']);
            $table->index(['tenant_id', 'income_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
