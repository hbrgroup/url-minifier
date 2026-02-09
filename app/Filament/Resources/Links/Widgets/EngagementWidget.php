<?php

namespace App\Filament\Resources\Links\Widgets;

use App\Models\Link;
use Filament\Support\Enums\Platform;
use Filament\Widgets\ChartWidget;

class EngagementWidget extends ChartWidget
{
    protected static string $resource = Platform::class;

    protected ?string $heading = 'Total Engagement';

    public ?Link $record = null;

    protected function getData(): array
    {
        return [
            'labels' => ['Total Cliques', 'Cliques Únicos'],
            'datasets' => [
                [
                    'label' => 'Engagement',
                    'data' => [$this->getTotalClicks(), $this->getUniqueClicks()],
                    'backgroundColor' => ['#4A90E2', '#50E3C2'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public function getTotalClicks(): int
    {
        return $this->record->clicks()->count();
    }

     public function getUniqueClicks(): int
     {
         return $this->record->clicks()->distinct('ip_address')->count('ip_address');
     }

}
