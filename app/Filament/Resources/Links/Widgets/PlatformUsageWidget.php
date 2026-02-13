<?php

namespace App\Filament\Resources\Links\Widgets;

use App\Models\Click;
use App\Models\Link;
use Filament\Support\Enums\Platform;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class PlatformUsageWidget extends ChartWidget
{
    protected static string $resource = Platform::class;

    protected ?string $heading = 'Uso por plataforma';

    public ?Link $record = null;

    protected function getData(): array
    {
         $clicksByPlatform = Click::where('link_id', $this->record->id)
            ->select('platform', \DB::raw('count(*) as total'))
            ->groupBy('platform')
            ->pluck('total', 'platform')
            ->toArray();

        return [
            'labels' => array_keys($clicksByPlatform),
            'datasets' => [
                [
                    'label' => 'Cliques por Plataforma',
                    'data' => array_values($clicksByPlatform),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                    ],
                    'borderColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                    ],
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array|RawJs|null
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                    ],
                ], 'scales' => [
                    'x' => [
                        'beginAtZero' => true,
                    ],
                ],
            ];
    }
}
