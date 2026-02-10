<?php

namespace App\Filament\Resources\Links;

use App\Filament\Resources\Links\Pages\CreateLink;
use App\Filament\Resources\Links\Pages\EditLink;
use App\Filament\Resources\Links\Pages\ListLinks;
use App\Filament\Resources\Links\Pages\ViewLink;
use App\Filament\Resources\Links\Schemas\LinkForm;
use App\Filament\Resources\Links\Schemas\LinkInfolist;
use App\Filament\Resources\Links\Tables\LinksTable;
use App\Models\CampaignLink;
use App\Models\Link;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $recordTitleAttribute = 'slug';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static string | UnitEnum | null $navigationGroup = "Links";

    public static function form(Schema $schema): Schema
    {
        return LinkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LinkInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LinksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\EngagementWidget::class,
            Widgets\BrowserUsageWidget::class,
            Widgets\PlatformUsageWidget::class,
            Widgets\ClickChartWidget::class,
            Widgets\CountryWidget::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLinks::route('/'),
            'create' => CreateLink::route('/create'),
            'view' => ViewLink::route('/{record}'),
            'edit' => EditLink::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (request()->route()->uri() == '_/links') {
            return parent::getEloquentQuery()
                ->whereDoesntHave('campaignLinks')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

}
