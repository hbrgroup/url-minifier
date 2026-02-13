<?php

namespace App\Filament\Resources\Links\Pages;

use App\Filament\Resources\Links\LinkResource;
use App\Filament\Resources\Links\Schemas\LinkInfolist;
use App\Filament\Resources\Links\Widgets\BrowserUsageWidget;
use App\Filament\Resources\Links\Widgets\ClickChartWidget;
use App\Filament\Resources\Links\Widgets\CountryMapWidget;
use App\Filament\Resources\Links\Widgets\EngagementWidget;
use App\Filament\Resources\Links\Widgets\PlatformUsageWidget;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewLink extends ViewRecord
{
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download.qrcode')
                ->label('Download QR Code')
                ->url(fn ($record) => route('links.qrcode', ['slug' => $record->slug]))
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return LinkInfolist::configure($schema);
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return 3;
    }

    protected function getFooterWidgets(): array
    {
        return [
            EngagementWidget::class,
            BrowserUsageWidget::class,
            PlatformUsageWidget::class,
            ClickChartWidget::class,
            CountryMapWidget::class,
        ];
    }
}
