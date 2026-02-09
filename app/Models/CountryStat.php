<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sushi\Sushi;

class CountryStat extends Model
{
    use Sushi;

    protected $fillable = [
        'link_id',
        'country',
        'clicks',
    ];

    public function getRows(): array
    {
        return Click::select('country', 'link_id')
            ->selectRaw('COUNT(*) as clicks, MAX(id) as id')
            ->whereNotNull('country')
            ->groupBy('country', 'link_id')
            ->get()
            ->toArray();
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}
