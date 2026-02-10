<?php

namespace App\Filament\Resources\Links\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables;
use Filament\Tables\Table;

class LinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->copyableState(fn ($record) => route('links.click', ['slug' => $record->slug]))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL original')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titulo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('qr_code')
                    ->label('QR Code')
                    ->boolean()
                    ->alignCenter()
                    ->sortable(),
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
