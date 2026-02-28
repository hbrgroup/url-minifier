<?php

namespace App\Filament\Resources\Files\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class FileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        TagsInput::make('sendTo')
                            ->label('Enviar para')
                            ->placeholder('Adicionar destinatário')
                            ->separator(',')
                            ->required(),
                        Textarea::make('message')
                            ->label('Mensagem')
                            ->rows(6)
                            ->maxLength(2048),
                        FileUpload::make('attachments')
                            ->label('Anexos')
                            ->hint('Tamanho máximo por ficheiro: 2GB.')
                            ->directory('files')
                            ->maxFiles(5)
                            ->maxSize(2048 * 1000) // 2GB em KB
                            ->acceptedFileTypes([
                                'image/*',
                                'application/pdf',
                                'text/plain',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/zip',
                                'application/x-zip-compressed',
                                'application/x-rar-compressed',
                                'application/x-7z-compressed',
                                'application/x-tar',
                            ])
                            ->storeFileNamesIn('attachment_file_names')
                            ->multiple()
                            ->downloadable()
                            ->required(),
                    ]),
            ]);
    }
}
