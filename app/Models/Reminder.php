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

    /**
     * Cast attributes to proper types
     */
    protected $casts = [
        'reminder_date' => 'date',
        'sent_at'       => 'datetime',
    ];

    /**
     * Relationships
     */
    public function installmentItem()
    {
        return $this->belongsTo(InstallmentItem::class);
    }
}
