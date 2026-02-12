<?php

namespace App\Models;

use App\Observers\FileObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([FileObserver::class])]
class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'sendTo' => 'array',
        'attachments' => 'array',
        'attachment_file_names' => 'array',
        'is_downloaded' => 'boolean',
    ];

    protected $fillable = [
        'message',
        'sendTo',
        'attachments',
        'attachment_file_names',
        'is_downloaded',
        'user_id',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->created_at->addDays(21)->isPast();
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
