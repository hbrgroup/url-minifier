<?php

namespace App\Filament\Resources\Campaigns\Widgets;

use App\Models\Campaign;
use App\Models\CountryStat;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class CountryWidget extends TableWidget
{
    protected static ?string $heading = 'Cliques por País';

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    public ?Campaign $record = null;

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
        $LinkIds = [];

        foreach ($this->record->links() as $link) {
            $LinkIds[] = $link->link->id;
        }

        return CountryStat::whereIn('link_id', $LinkIds);
    }

    public function getTableRecordKey(Model|array $record): string
    {
        return (string)$record->id;
    }
}
