<?php

namespace App\Filament\Resources\LatestNews\Pages;

use App\Filament\Resources\LatestNews\LatestNewResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLatestNew extends CreateRecord
{
    protected static string $resource = LatestNewResource::class;
}
