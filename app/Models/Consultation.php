<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'preferred_date',
        'preferred_slot',
        'notes',
        'scheduled_at',
        'meeting_link',
        'discussion_history',
        'status',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'scheduled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
