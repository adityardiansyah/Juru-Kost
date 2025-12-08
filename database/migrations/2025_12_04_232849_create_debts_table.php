<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('creditor_name'); // Nama pemberi pinjaman
            $table->string('debt_type')->default('other'); // bank, supplier, personal, other
            $table->decimal('principal_amount', 15, 2); // Pokok hutang
            $table->decimal('interest_rate', 5, 2)->default(0); // Bunga per tahun (%)
            $table->decimal('total_amount', 15, 2); // Total yang harus dibayar
            $table->date('start_date'); // Tanggal mulai
            $table->date('end_date'); // Tanggal selesai
            $table->decimal('installment_amount', 15, 2); // Cicilan per periode
            $table->string('installment_frequency')->default('monthly'); // monthly, quarterly, yearly
            $table->integer('total_installments')->default(0); // Jumlah cicilan
            $table->enum('status', ['active', 'paid_off', 'overdue'])->default('active');
            $table->text('description')->nullable();
            $table->string('contract_file')->nullable(); // File kontrak/perjanjian
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'end_date']);
            $table->index('debt_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
