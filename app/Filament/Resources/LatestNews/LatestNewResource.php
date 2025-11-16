<?php

namespace App\Filament\Resources\LatestNews;

use App\Filament\Resources\LatestNews\Pages\CreateLatestNew;
use App\Filament\Resources\LatestNews\Pages\EditLatestNew;
use App\Filament\Resources\LatestNews\Pages\ListLatestNews;
use App\Filament\Resources\LatestNews\Schemas\LatestNewForm;
use App\Filament\Resources\LatestNews\Tables\LatestNewsTable;
use App\Models\LatestNew;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LatestNewResource extends Resource
{
    protected static ?string $model = LatestNew::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $recordTitleAttribute = 'title';
    protected static string|\UnitEnum|null $navigationGroup = 'Data Management';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return LatestNewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LatestNewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLatestNews::route('/'),
            'create' => CreateLatestNew::route('/create'),
            'edit' => EditLatestNew::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.latest_news');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resource.latest_news');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.latest_news');
    }
}
