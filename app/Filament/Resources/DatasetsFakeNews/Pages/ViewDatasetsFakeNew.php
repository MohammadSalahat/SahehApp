<?php

namespace App\Filament\Resources\DatasetsFakeNews\Pages;

use App\Filament\Resources\DatasetsFakeNews\DatasetsFakeNewResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDatasetsFakeNew extends ViewRecord
{
    protected static string $resource = DatasetsFakeNewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
