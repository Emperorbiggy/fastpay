<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Fillable properties for mass-assignment
    protected $fillable = [
        'transaction_id',
        'amount',
        'type',
        'status',
        'method',
        'description',
        'account_number',
        'account_name',
        'bank_name',
        'user_id',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

