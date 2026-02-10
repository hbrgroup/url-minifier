<?php

namespace App\Filament\Resources\Campaigns\Widgets;

use App\Models\Campaign;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class BrowserUsageWidget extends ChartWidget
{
    protected ?string $heading = 'Uso por navegador';

    protected ?string $pollingInterval = null;

    public ?Campaign $record = null;

    protected function getData(): array
    {
        $clicksByBrowser = $this->record->clicks()
            ->select('browser', \DB::raw('count(*) as total'))
            ->groupBy('browser')
            ->pluck('total', 'browser')
            ->toArray();

        return [
            'labels' => array_keys($clicksByBrowser),
            'datasets' => [
                [
                    'label' => 'Uso por navegador',
                    'data' => array_values($clicksByBrowser),
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
            'indexAxis' => 'x',
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
