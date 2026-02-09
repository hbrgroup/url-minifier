<?php

namespace App\Filament\Resources\Links\Widgets;

use App\Models\Click;
use App\Models\CountryStat;
use App\Models\Link;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class CountryWidget extends TableWidget
{
    protected static ?string $heading = 'Cliques por País';

    protected int | string | array $columnSpan = 'full';

    public ?Link $record = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getTableQuery())
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('country')
                    ->label('País')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('clicks')
                    ->label('Cliques')
                    ->sortable()
                    ->alignEnd()
                    ->width(140),
            ]);
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        return CountryStat::where('link_id', $this->record->id);
    }

    public function getTableRecordKey(Model|array $record): string
    {
        return (string)$record->id;
    }
}
