<?php

namespace App\Filament\Resources\Sources\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.sources.source_information'))
                    ->description(__('filament.sources.source_info_description'))
                    ->icon('heroicon-m-globe-alt')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.sources.name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.sources.placeholders.name'))
                            ->columnSpan(2),

                        TextInput::make('url')
                            ->label(__('filament.sources.website_url'))
                            ->url()
                            ->required()
                            ->placeholder(__('filament.sources.placeholders.url'))
                            ->columnSpan(2),

                        Textarea::make('description')
                            ->label(__('filament.sources.description'))
                            ->rows(4)
                            ->placeholder(__('filament.sources.placeholders.description'))
                            ->columnSpanFull(),
                    ]),

                Section::make(__('filament.sources.reliability_assessment'))
                    ->description(__('filament.sources.reliability_description'))
                    ->icon('heroicon-m-shield-check')
                    ->columns(2)
                    ->schema([
                        TextInput::make('reliability_score')
                            ->label(__('filament.sources.reliability_score'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->step(0.01)
                            ->minValue(0)
                            ->maxValue(1)
                            ->suffix('/1.0')
                            ->helperText(__('filament.sources.helper_texts.reliability_score'))
                            ->columnSpan(1),

                        Toggle::make('is_active')
                            ->label(__('filament.sources.is_active'))
                            ->helperText(__('filament.sources.helper_texts.is_active'))
                            ->default(true)
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
