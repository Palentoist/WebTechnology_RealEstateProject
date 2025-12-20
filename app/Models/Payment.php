<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'installment_item_id',
        'payment_date',
        'amount',
        'payment_method',
        'transaction_id',
        'bank_name',
        'received_by',
    ];

    // Relationships
    public function installmentItem()
    {
        return $this->belongsTo(InstallmentItem::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function paymentSlip()
    {
        return $this->hasOne(PaymentSlip::class);
    }
}