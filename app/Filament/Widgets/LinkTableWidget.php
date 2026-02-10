<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Links\LinkResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Link;
use Illuminate\Database\Eloquent\Relations\Relation;

class LinkTableWidget extends TableWidget
{
    protected static ?string $heading = 'Links Recentes';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getTableQuery())
            ->defaultPaginationPageOption(25)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->width(60),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Link')
                    ->formatStateUsing(fn (Link $record): string => route('links.click', ['slug' => $record->slug]))
                    ->copyable()
                    ->copyableState(fn (Link $record) => route('links.click', ['slug' => $record->slug]))
                    ->color('primary')
                    ->width(220),
                Tables\Columns\ImageColumn::make('image')
                    ->label('QR Code')
                    ->imageWidth(75)
                    ->imageHeight('auto')
                    ->getStateUsing(fn (Link $record) => $record->qr_code ? route('links.qrcode', ['slug' => $record->slug]) : null)
                    ->openUrlInNewTab()
                    ->alignCenter()
                    ->width(100),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL original'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado a')
                    ->date('d-m-Y H:i')
                    ->width(160),
            ])
            ->recordActions([
                Action::make('qrcode.download')
                    ->hiddenLabel()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->tooltip('Download QR Code')
                    ->visible(fn (Link $record) => $record->qr_code)
                    ->url(fn (Link $record) => route('links.qrcode', ['slug' => $record->slug]))
                    ->openUrlInNewTab(),
                Action::make('analyse')
                    ->hiddenLabel()
                    ->icon('heroicon-o-chart-bar')
                    ->tooltip('Análise')
                    ->url(fn (Link $record) => LinkResource::getUrl('view', ['record' => $record->slug]))
            ]);
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        return Link::query()
            ->whereDoesntHave('campaignLinks')
            ->orderByDesc('created_at')
            ->limit(100);
    }
}
