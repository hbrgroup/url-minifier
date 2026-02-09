<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'url',
        'title',
        'qr_code',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function clicks() : HasMany
    {
        return $this->hasMany(Click::class);
    }
}
