<?php

namespace App\Filament\Resources\Campaigns\Widgets;

use App\Models\Campaign;
use App\Models\CountryStat;
use Filament\Widgets\Widget;

class CountryMapWidget extends Widget
{
    protected string $view = 'filament.widgets.country-map-widget';

    protected static ?string $heading = 'Cliques por País';

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    public ?Campaign $record = null;

    protected function getViewData(): array
    {
        // Get all link IDs for this campaign
        $linkIds = $this->record->links()
            ->pluck('link_id')
            ->toArray();

        // Get country statistics for these links
        $countryStats = CountryStat::whereIn('link_id', $linkIds)
            ->selectRaw('country, SUM(clicks) as clicks')
            ->groupBy('country')
            ->get()->map(function ($stat) {
                return [
                    'country' => $stat->country,
                    'clicks' => (int) $stat->clicks,
                ];
            })->toArray();

        return [
            'countryData' => $countryStats,
        ];
    }
}
