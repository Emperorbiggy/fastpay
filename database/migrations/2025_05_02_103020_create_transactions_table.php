<?php

// database/migrations/xxxx_xx_xx_create_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_id')->unique(); // Unique TX reference
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['credit', 'debit']);
            $table->enum('status', ['pending', 'successful', 'failed']);
            $table->string('method'); // e.g., FastPay, Wallet, Bank Transfer
            $table->string('description')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};
