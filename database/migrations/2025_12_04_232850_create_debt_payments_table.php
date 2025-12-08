<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debt_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debt_id')->constrained()->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('expense_id')->nullable()->constrained()->onDelete('set null'); // Link to expense
            $table->date('payment_date')->nullable(); // Tanggal bayar (null jika belum dibayar)
            $table->date('due_date'); // Tanggal jatuh tempo
            $table->decimal('amount', 15, 2); // Total dibayar
            $table->decimal('principal_paid', 15, 2)->default(0); // Porsi pokok
            $table->decimal('interest_paid', 15, 2)->default(0); // Porsi bunga
            $table->decimal('late_fee', 15, 2)->default(0); // Denda keterlambatan
            $table->enum('status', ['paid', 'pending', 'overdue'])->default('pending');
            $table->string('proof_file')->nullable(); // Bukti pembayaran
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['debt_id', 'payment_date']);
            $table->index(['tenant_id', 'status']);
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debt_payments');
    }
};
