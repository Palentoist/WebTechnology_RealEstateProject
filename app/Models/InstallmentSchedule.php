<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'total_installments',
        'start_date',
        'end_date',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function installmentItems()
    {
        return $this->hasMany(InstallmentItem::class, 'schedule_id');
    }
}