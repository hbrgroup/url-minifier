<?php


namespace App\Filament\Resources\Files\Pages;

use App\Filament\Resources\Files\FileResource;
use App\Filament\Resources\Files\Schemas\FileInfolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewFile extends ViewRecord
{
    protected static string $resource = FileResource::class;

    public function form(Schema $schema): Schema
    {
        return FileInfolist::configure($schema);
    }

}
