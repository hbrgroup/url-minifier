<?php

namespace App\Filament\Widgets;

use App\Models\Click;
use App\Models\Link;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Links', $this->getTotalLinks())
                ->description('Total de links encurtados'),

            Stat::make('Cliques Totais', $this->getTotalClicks())
                ->description('Total de cliques nos links'),

            Stat::make('Cliques Únicos', $this->getTotalUniqueClicks())
                ->description('Total de cliques únicos nos links'),

            Stat::make('Total de QR codes', $this->getTotalQrCodes())
                ->description('Total de links que possuem QR code gerado'),
        ];
    }

    private function getTotalLinks(): int
    {
        return Link::count();
    }

    protected function getTotalClicks(): int
    {
        return Link::withCount('clicks')->get()->sum('clicks_count');
    }

    private function getTotalUniqueClicks(): int
    {
        // Para obter o total de cliques únicos, podemos contar os cliques distintos por IP agrupados por link
        return Click::query()
            ->select('link_id', 'ip_address')
            ->distinct()
            ->count();
    }

    private function getTotalQrCodes(): int
    {
        return Link::where('qr_code', true)->count();
    }
}
