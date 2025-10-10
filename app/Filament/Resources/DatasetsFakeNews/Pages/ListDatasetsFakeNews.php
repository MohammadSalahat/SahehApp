<?php

namespace App\Filament\Resources\DatasetsFakeNews\Pages;

use App\Filament\Resources\DatasetsFakeNews\DatasetsFakeNewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDatasetsFakeNews extends ListRecords
{
    protected static string $resource = DatasetsFakeNewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
