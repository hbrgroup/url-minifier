<?php

namespace App\Filament\Resources\Files\Tables;

use App\Models\File;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables;
use Filament\Tables\Table;

class FilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('link.slug')
                    ->label('Link')
                    ->copyable()
                    ->copyableState(fn ($record) => route('links.click', ['slug' => $record->link->slug]))
                    ->width(100),
                Tables\Columns\TextColumn::make('message')
                    ->label('Mensagem')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sendTo')
                    ->label('Enviado para')
                    ->separator(',')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_files')
                    ->label('Total de ficheiros')
                    ->getStateUsing(fn (File $record) =>  count($record->attachments))
                    ->alignEnd()
                    ->sortable()
                    ->width(100),
                Tables\Columns\IconColumn::make('is_downloaded')
                    ->label('Descarregado')
                    ->boolean()
                    ->alignCenter()
                    ->sortable()
                    ->width(100),
                Tables\Columns\IconColumn::make('is_expired')
                    ->label('Expirado')
                    ->tooltip(function (File $record) {
                        if (!$record->is_expired)
                            return 'Este ficheiro expirará ' . $record->created_at->addDays(21)->diffForHumans();
                        return 'Este ficheiro expirou';
                    })
                    ->boolean()
                    ->alignCenter()
                    ->sortable()
                    ->width(100),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado a')
                    ->date('d-m-Y H:i')
                    ->sortable()
                    ->width(140),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->hiddenLabel(),
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
