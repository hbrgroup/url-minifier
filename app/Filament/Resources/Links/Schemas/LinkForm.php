<?php

namespace App\Filament\Resources\Links\Schemas;

use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Forms;
use Illuminate\Support\Str;

class LinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->disabled()
                    ->required()
                    ->dehydrated()
                    ->unique(column: 'slug', ignoreRecord: true),
                Forms\Components\TextInput::make('url')
                    ->label('URL original')
                    ->required()
                    ->url()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Set $set) {
                        if ($operation === 'create') {
                            $generatedSlug = Str::random(6);
                            $set('slug', $generatedSlug);
                        }
                    })
                    ->columnSpan(3),
                Forms\Components\TextInput::make('title')
                    ->label('Titulo')
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('qr_code')
                    ->label('Gerar QR Code')
                    ->default(false)
                    ->columnSpanFull(),
            ]);
    }
}
