<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'url',
        'title',
        'qr_code',
        'type_of_link',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function clicks() : HasMany
    {
        return $this->hasMany(Click::class);
    }

    public function campaignLinks(): HasMany
    {
        return $this->hasMany(CampaignLink::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public static function createSlug() : string
    {
        $created = false;

        do {
            $rnd = Str::random(6);
            if (!Link::where('slug', $rnd)->exists())
                $created = true;

        } while (!$created);

        return $rnd;
    }
}
