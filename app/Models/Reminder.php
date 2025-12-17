<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'installment_item_id',
        'reminder_date',
        'type',
        'msg',
        'status',
        'sent_at',
    ];

    // Relationships
    public function installmentItem()
    {
        return $this->belongsTo(InstallmentItem::class);
    }
}