<?php

namespace App\Models;

use App\Observers\CampaignObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([CampaignObserver::class])]
class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'channels_id' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'description',
        'url',
        'channels_id',
        'start_date',
        'end_date',
    ];

    public function links(): HasMany
    {
        return $this->hasMany(CampaignLink::class);
    }

    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class, 'channels_id', 'id');
    }

    public function clicks(): HasManyThrough
    {
        return $this->hasManyThrough(Click::class, CampaignLink::class, 'campaign_id', 'link_id', 'id', 'link_id');
    }

    /**
     * Retorna o número de cliques por dia e por link para a campanha.
     *
     * @return array
     */
    public function clicksOverTime(): array
    {
        // SELECT c.name AS channel_name,
        //       date(clicks.created_at) as click_date,
        //       count(clicks.id) AS total_clicks
        //FROM `url-minifier`.campaign_links
        //    INNER JOIN `url-minifier`.links ON campaign_links.link_id = links.id
        //    INNER JOIN `url-minifier`.clicks ON links.id = clicks.link_id
        //    INNER JOIN `url-minifier`.channels c on campaign_links.channel_id = c.id
        //WHERE campaign_id = 1
        //GROUP BY c.name, `click_date`

        return CampaignLink::query()
            ->selectRaw('channels.name AS channel_name, DATE(clicks.created_at) AS click_date, COUNT(clicks.id) AS total_clicks')
            ->join('clicks', 'clicks.link_id', '=', 'campaign_links.link_id')
            ->join('channels', 'campaign_links.channel_id', '=', 'channels.id')
            ->groupBy('channels.name', 'click_date')
            ->get()->toArray();
    }
}
