<?php

namespace App\Filament\Resources\DatasetsFakeNews\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DatasetsFakeNewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('detected_at')
                    ->required(),
                TextInput::make('confidence_score')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('origin_dataset_name'),
                Toggle::make('added_by_ai')
                    ->required(),
                TextInput::make('content_hash')
                    ->required(),
            ]);
    }
}
