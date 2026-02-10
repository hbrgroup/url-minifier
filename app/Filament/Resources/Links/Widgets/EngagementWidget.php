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
            'labels' => ['Qr Code', 'Outros'],
            'datasets' => [
                [
                    'label' => 'Engagement',
                    'data' => [$this->getQrCodeClicks(), $this->getDirectClicks()],
                    'backgroundColor' => ['#4A90E2', '#50E3C2'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function getDirectClicks(): int
    {
        return $this->record->clicks()->where('qr_code', false)->count();
    }

    private function getQrCodeClicks(): int
    {
        return $this->record->clicks()->where('qr_code', true)->count();
    }

}
