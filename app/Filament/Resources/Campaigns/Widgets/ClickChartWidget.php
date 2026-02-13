<?php

namespace App\Filament\Resources\Campaigns\Widgets;

use App\Models\Campaign;
use Filament\Widgets\ChartWidget;

class ClickChartWidget extends ChartWidget
{
    protected ?string $heading = 'Cliques no periodo de tempo';

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected ?string $maxHeight = '300px';

    public ?Campaign $record = null;

    protected function getData(): array
    {
        $data = $this->record->clicksOverTime();

        $labels = array_map(
            static fn(array $row): string => $row['channel_name'],
        $data);

        foreach ($data as $row) {
            $channel = $row['channel_name'];
            $datasets[$channel][] = [
                'x' => $row['click_date'],
                'y' => $row['total_clicks'],
            ];
        }

        $datasets = array_map(
             static function(array $points, string $label) {
                // cria uma cor
                $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

                 // retorna o dataset formatado para o Chart.js
                 return [
                     'label' => $label,
                     'data' => $points,
                     'borderColor' => $color,
                     'backgroundColor' => $color,
                     'fill' => false,
                 ];
             }, $datasets ?? [], array_keys($datasets ?? []),
        );

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => true,
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ], 'scales' => [
                'x' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
