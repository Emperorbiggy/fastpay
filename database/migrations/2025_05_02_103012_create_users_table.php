<?php

// database/migrations/xxxx_xx_xx_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->unique(); // Unique user identifier
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('token')->nullable(); // For auth sessions or Firebase
            $table->string('password');
            $table->string('full_name');
            $table->string('phone_number')->unique();
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('verification_code')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('passcode')->nullable(); // Optional passcode
            $table->string('pin')->nullable(); // Transaction PIN (store hashed)
            $table->string('id_number')->nullable();
            $table->string('id_type')->nullable();
            $table->string('profile_image')->nullable(); // URL to profile image
            $table->string('id_front_image_url')->nullable();
            $table->string('id_back_image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
