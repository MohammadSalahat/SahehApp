<?php

namespace App\Filament\Resources\DatasetsFakeNews;

use App\Filament\Resources\DatasetsFakeNews\Pages\CreateDatasetsFakeNew;
use App\Filament\Resources\DatasetsFakeNews\Pages\EditDatasetsFakeNew;
use App\Filament\Resources\DatasetsFakeNews\Pages\ListDatasetsFakeNews;
use App\Filament\Resources\DatasetsFakeNews\Pages\ViewDatasetsFakeNew;
use App\Filament\Resources\DatasetsFakeNews\Schemas\DatasetsFakeNewForm;
use App\Filament\Resources\DatasetsFakeNews\Schemas\DatasetsFakeNewInfolist;
use App\Filament\Resources\DatasetsFakeNews\Tables\DatasetsFakeNewsTable;
use App\Models\DatasetsFakeNew;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DatasetsFakeNewResource extends Resource
{
    protected static ?string $model = DatasetsFakeNew::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Data set';

    public static function form(Schema $schema): Schema
    {
        return DatasetsFakeNewForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DatasetsFakeNewInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DatasetsFakeNewsTable::configure($table);
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
            'index' => ListDatasetsFakeNews::route('/'),
            'create' => CreateDatasetsFakeNew::route('/create'),
            'view' => ViewDatasetsFakeNew::route('/{record}'),
            'edit' => EditDatasetsFakeNew::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
