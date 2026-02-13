<?php

namespace App\Filament\Resources\Links\Widgets;

use App\Models\CountryStat;
use App\Models\Link;
use Filament\Widgets\Widget;

class CountryMapWidget extends Widget
{
    protected string $view = 'filament.widgets.country-map-widget';

    protected static ?string $heading = 'Cliques por País';

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    public ?Link $record = null;

    protected function getViewData(): array
    {
        // Get country statistics for these links
        $countryStats = CountryStat::where('link_id', $this->record->id)
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
