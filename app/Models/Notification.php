<?php

namespace App\Models;

use AllowDynamicProperties;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[AllowDynamicProperties]
class Notification extends Model
{
    protected $fillable = [
        'text',
        'channel',
        'user_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
