<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'plan_name',
        'duration_months',
        'down_payment_percentage',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'plan_id');
    }
}