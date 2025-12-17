<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'name',
        'location',
        'description',
        'total_units',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function unitCategories()
    {
        return $this->hasMany(UnitCategory::class);
    }

    public function installmentPlans()
    {
        return $this->hasMany(InstallmentPlan::class);
    }
}