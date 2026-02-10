<?php

namespace App\Filament\Resources\Campaigns\Pages;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Filament\Resources\Campaigns\Widgets\BrowserUsageWidget;
use App\Filament\Resources\Campaigns\Widgets\ClickChartWidget;
use App\Filament\Resources\Campaigns\Widgets\CountryWidget;
use App\Filament\Resources\Campaigns\Widgets\PlatformUsageWidget;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;

class ViewCampaign extends ViewRecord
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getFooterWidgetsColumns(): int
    {
        return 2;
    }

    public function getFooterWidgets(): array
    {
        return [
            ClickChartWidget::class,
            BrowserUsageWidget::class,
            PlatformUsageWidget::class,
            CountryWidget::class,
        ];
    }
}
