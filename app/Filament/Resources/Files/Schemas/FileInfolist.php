<?php

namespace App\Filament\Resources\Files\Schemas;

use App\Filament\Infolists\Components\AttachmentEntry;
use App\Models\File;
use Carbon\Carbon;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class FileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make()
                    ->columns(12)
                    ->schema([
                        TextEntry::make('link.slug')
                            ->label('Link')
                            ->copyable()
                            ->copyableState(fn (File $record) => route('links.click', ['slug' => $record->link->slug])),
                        TextEntry::make('created_at')
                            ->label('Criado em')
                            ->dateTime('d-m-Y H:i'),
                        IconEntry::make('is_downloaded')
                            ->label('Descarregado')
                            ->color(fn (File $record) => $record->is_downloaded ? 'success' : 'danger')
                            ->getStateUsing(function (File $record) {
                                return $record->is_downloaded ? 'heroicon-o-check' : 'heroicon-o-x-mark';
                            }),
                    ]),
                TextEntry::make('sendTo')
                    ->label('Enviado para')
                    ->badge()
                    ->separator(','),
                TextEntry::make('message')
                    ->label('Mensagem')
                    ->placeholder('-'),
                AttachmentEntry::make('attachments')
                    ->getStateUsing(function (File $record) {
                        return collect($record->attachments)->map(function ($attachment) use ($record) {
                            if (!Storage::exists($attachment)) {
                                return [
                                    'name' => $record->attachment_file_names[$attachment] ?? 'Ficheiro não encontrado',
                                    'size' => 0,
                                    'path' => null,
                                    'url'  => null,
                                ];
                            }

                            return [
                                'name' => $record->attachment_file_names[$attachment],
                                'size' => filesize(Storage::path($attachment)),
                                'path' => Storage::path($attachment),
                                'url'  => Storage::temporaryUrl($attachment, now()->addHours(24)),
                            ];
                        })->toArray();
                    })
                    ->label('Ficheiros'),
            ]);
    }
}
