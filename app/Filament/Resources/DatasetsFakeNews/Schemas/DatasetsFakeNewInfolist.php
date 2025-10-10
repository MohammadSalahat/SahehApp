<?php

namespace App\Filament\Resources\DatasetsFakeNews\Schemas;

use App\Models\DatasetsFakeNew;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DatasetsFakeNewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('content')
                    ->columnSpanFull(),
                TextEntry::make('detected_at')
                    ->dateTime(),
                TextEntry::make('confidence_score')
                    ->numeric(),
                TextEntry::make('origin_dataset_name')
                    ->placeholder('-'),
                IconEntry::make('added_by_ai')
                    ->boolean(),
                TextEntry::make('content_hash'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (DatasetsFakeNew $record): bool => $record->trashed()),
            ]);
    }
}
