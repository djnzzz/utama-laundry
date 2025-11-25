<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_sn', 'name', 'phone', 'service_code', 
        'service_name', 'service_type', 'paket', 'type', 
        'estimasi_berat', 'estimasi_price', 'final_weight', 
        'final_price', 'total_harga', 'pakaian_dalam', 
        'jumlah_pakaian_dalam', 'order_date', 'underwear_count', 
        'jumlah_item', 'payment_method', 'payment_status', 
        'status', 'cancelled_at', 'cancelled_by', 'status_cucian'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke PaymentProof
    public function paymentProof()
    {
        return $this->hasOne(PaymentProof::class);
    }

    // Generate order serial number otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_sn) {
                $date = now()->format('Ymd');
                $lastOrder = static::whereDate('created_at', now())->latest('id')->first();
                $number = $lastOrder ? intval(substr($lastOrder->order_sn, -6)) + 1 : 1;
                $order->order_sn = 'UL-' . $date . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}