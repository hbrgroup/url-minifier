<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Click extends Model
{
    protected $fillable = [
        'link_id',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device',
        'country',
        'country_code',
        'referrer',
        'referrer_type',
    ];

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}
