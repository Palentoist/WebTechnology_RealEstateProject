<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'unit_id',
        'plan_id',
        'booking_date',
        'total_amount',
        'down_payment',
        'status',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class, 'plan_id');
    }

    public function installmentSchedule()
    {
        return $this->hasOne(InstallmentSchedule::class);
    }
}