<?php

namespace App\Filament\Resources\Campaigns\Tables;

use App\Models\Campaign;
use App\Models\Channel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('name')
                    ->label('Nome da campanha')
                    ->searchable(),
                TextColumn::make('url')
                    ->label('URL original')
                    ->searchable(),
                TextColumn::make('channels_id')
                    ->label('Canais')
                    ->getStateUsing(function (Campaign $record) {
                        return array_map(function($id) {
                            return Channel::where('id', $id)->value('name') ?? 'Desconecido';
                        }, $record->channels_id ?? []);
                    })
                    ->badge()
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Data de Início')
                    ->date('d-m-Y')
                    ->sortable()
                    ->width(120),
                TextColumn::make('end_date')
                    ->label('Data de início')
                    ->date('d-m-Y')
                    ->sortable()
                    ->width(120),
                TextColumn::make('created_at')
                    ->label('Criado a')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->width(140),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->hiddenLabel()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
