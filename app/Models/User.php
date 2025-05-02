<?php

// app/Models/User.php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;  // Add this import
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;  // Add HasApiTokens here

    // Table Name (if you want to customize it, you can specify the name)
    protected $table = 'users';

    // Fillable properties (attributes that can be mass-assigned)
    protected $fillable = [
        'email',
        'user_id',
        'username',
        'token',
        'password',
        'full_name',
        'phone_number',
        'address',
        'state',
        'country',
        'city',
        'verification_code',
        'is_verified',
        'passcode',
        'pin',
        'id_number',
        'id_type',
        'profile_image',
        'id_front_image_url',
        'id_back_image_url',
    ];

    // Hidden properties (we don't want these to be exposed to arrays or JSON)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast properties (ensure correct types are casted)
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'verification_code' => 'string',
    ];

    // Relationships

    // One-to-many relationship with Transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // One-to-one relationship with Wallet
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
