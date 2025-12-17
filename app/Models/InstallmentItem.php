<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'installment_number',
        'due_date',
        'amount',
        'paid_amount',
        'status',
    ];

    // Relationships
    public function schedule()
    {
        return $this->belongsTo(InstallmentSchedule::class, 'schedule_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
}