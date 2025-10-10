<?php

namespace App\Filament\Resources\DatasetsFakeNews\Pages;

use App\Filament\Resources\DatasetsFakeNews\DatasetsFakeNewResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDatasetsFakeNew extends EditRecord
{
    protected static string $resource = DatasetsFakeNewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
