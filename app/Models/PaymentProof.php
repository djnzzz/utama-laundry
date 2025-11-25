<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    protected $fillable = [
        'order_id',
        'file_path',
        'status',
        'uploaded_at',
        'verified_at',
        'verified_by',
        'rejection_reason'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke User (verifier)
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}