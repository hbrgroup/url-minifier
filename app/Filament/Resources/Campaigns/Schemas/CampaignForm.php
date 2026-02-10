<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use App\Models\Channel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                Section::make('Informações da campanha')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome da campanha')
                            ->required(),
                        TextInput::make('url')
                            ->label('URL original')
                            ->url()
                            ->required(),
                        Textarea::make('description')
                            ->label('Descrição')
                            ->rows(4)
                            ->maxLength(2048),
                    ])->columnSpan(3),
                Section::make('Configurações')
                    ->schema([
                        Select::make('channels_id')
                            ->options(fn () => Channel::pluck('name', 'id'))
                            ->label('Canais')
                            ->searchable(false)
                            ->multiple()
                            ->required(),
                        DatePicker::make('start_date')
                            ->label('Data de início')
                            ->required()
                            ->default(now()),
                        DatePicker::make('end_date')
                            ->label('Data de fim'),
                    ])->columns(1),
            ]);
    }
}
