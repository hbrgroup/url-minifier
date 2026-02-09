<?php

namespace App\Filament\Resources\Links\Widgets;

use App\Models\Link;
use Filament\Widgets\ChartWidget;

class ClickChartWidget extends ChartWidget
{
    protected ?string $heading = 'Cliques ao longo do tempo';

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    public ?Link $record = null;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Clicks',
                    'data' => $this->record
                        ? $this->record->clicks()
                            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                            ->groupBy('date')
                            ->orderBy('date')
                            ->get()
                            ->map(fn ($click) => [
                                'x' => $click->date,
                                'y' => $click->count,
                            ])
                        : [],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ], 'scales' => [
                'x' => [
                    'type' => 'time',
                    'time' => [
                        'unit' => 'day',
                    ],
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
