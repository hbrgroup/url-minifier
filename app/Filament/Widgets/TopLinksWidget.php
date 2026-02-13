<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Links\LinkResource;
use App\Models\Link;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class TopLinksWidget extends TableWidget
{
    protected static ?string $heading = 'Top 5 Links';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getTableQuery())
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('url')
                    ->label('Link')
                    ->formatStateUsing(fn (Link $record): string => route('links.click', ['slug' => $record->slug]))
                    ->url(fn (Link $record) => LinkResource::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('clicks_count')
                    ->label('Cliques')
                    ->alignEnd()
                    ->width(120),
            ]);
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        return Link::query()
            ->withCount('clicks')
            ->orderByDesc('clicks_count')
            ->limit(5);
    }
}
