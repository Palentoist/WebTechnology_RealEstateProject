<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'title',
        'details',
        'read_at',
        'read_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function readBy()
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function markAsRead($userId)
    {
        $this->update([
            'read_at' => now(),
            'read_by' => $userId,
        ]);
    }
}


