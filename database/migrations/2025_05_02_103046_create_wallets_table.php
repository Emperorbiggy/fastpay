<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user_id should reference users.id
            $table->decimal('amount', 15, 2)->default(0.00); // User's current wallet balance
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('wallets');
    }
};
