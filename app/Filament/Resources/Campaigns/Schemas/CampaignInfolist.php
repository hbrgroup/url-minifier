<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use App\Filament\Resources\Links\LinkResource;
use App\Models\Campaign;
use App\Models\CampaignLink;
use App\Models\Channel;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CampaignInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                TextEntry::make('name')
                    ->label('Nome da campanha')
                    ->columnSpan(11),
                TextEntry::make('created_at')
                    ->label('Criado a')
                    ->dateTime('d-m-Y H:i')
                    ->placeholder('-'),
                TextEntry::make('url')
                    ->label('URL original')
                    ->url(fn(Campaign $record) => $record->url)
                    ->openUrlInNewTab()
                    ->columnSpan(8),
                TextEntry::make('channels_id')
                    ->label('Canais')
                    ->getStateUsing(function (Campaign $record) {
                        return array_map(function($id) {
                            return Channel::where('id', $id)->value('name') ?? 'Sem canal';
                        }, $record->channels_id ?? []);
                    })
                    ->badge()
                    ->columnSpan(2),
                TextEntry::make('start_date')
                    ->label('Data de início')
                    ->date('d-m-Y'),
                TextEntry::make('end_date')
                    ->label('Data de fim')
                    ->placeholder('-')
                    ->date('d-m-Y'),
                TextEntry::make('description')
                    ->label('Descrição')
                    ->visible(fn (Campaign $record) => !empty($record->description))
                    ->columnSpanFull(),
                RepeatableEntry::make('links')
                    ->dense()
                    ->contained(false)
                    ->columns(12)
                    ->schema([
                        TextEntry::make('channel.name')
                            ->hiddenLabel()
                            ->badge()
                            ->alignEnd()
                            ->columnSpan(1),
                        TextEntry::make('link.slug')
                            ->hiddenLabel()
                            ->formatStateUsing(fn (CampaignLink $record): string => route('links.click', ['slug' => $record->link->slug]))
                            ->copyable()
                            ->copyableState(fn(CampaignLink $record) => LinkResource::getUrl('view', ['record' => $record->link->slug]))
                            ->columnSpan(11),
                    ])->columnSpanFull()

            ]);
    }
}
