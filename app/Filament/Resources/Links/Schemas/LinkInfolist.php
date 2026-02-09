<?php

namespace App\Filament\Resources\Links\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Infolists;

class LinkInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                Grid::make()
                    ->columns(12)
                    ->columnSpanFull()
                    ->components([
                        Grid::make()
                            ->columns(4)
                            ->components([
                                Infolists\Components\TextEntry::make('slug')
                                    ->label('Slug')
                                    ->copyable()
                                    ->copyableState(fn ($record) => route('links.click', ['slug' => $record->slug]))
                                    ->columnSpan(1),
                                Infolists\Components\TextEntry::make('url')
                                    ->label('URL original')
                                    ->url(fn ($record) => $record->url)
                                    ->openUrlInNewTab()
                                    ->columnSpan(3),
                                Infolists\Components\TextEntry::make('title')
                                    ->label('Titulo')
                                    ->visible(fn ($record) => !is_null($record->title))
                                    ->columnSpanFull(),
                            ])->columnSpan(10),
                        Infolists\Components\ImageEntry::make('image')
                            ->label('QR Code')
                            ->hiddenLabel()
                            ->visible(fn ($record) => $record->qr_code)
                            ->alignEnd()
                            ->imageWidth(150)
                            ->imageHeight('auto')
                            ->defaultImageUrl(fn ($record) => route('links.qrcode', ['slug' => $record->slug]))
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
