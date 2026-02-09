<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardStatsWidget;
use App\Filament\Widgets\LinkTableWidget;
use App\Filament\Widgets\TopLinksWidget;
use App\Filament\Widgets\TotalBrowserUsageWidget;
use App\Filament\Widgets\TotalPlatformUsageWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = 0;

    public function getHeading(): string
    {
        $name = auth()->user()->name;
        return "Olá, {$name}";
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 3;
    }

    public function getHeaderWidgets(): array
    {
        return [
            TopLinksWidget::class,
            TotalBrowserUsageWidget::class,
            TotalPlatformUsageWidget::class,
            DashboardStatsWidget::class,
            LinkTableWidget::class,
        ];
    }

}
