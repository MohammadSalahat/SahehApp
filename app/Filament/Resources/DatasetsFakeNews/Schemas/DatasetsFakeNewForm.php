<?php

namespace App\Filament\Resources\DatasetsFakeNews\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DatasetsFakeNewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.datasets_fake_news.article_information'))
                    ->description(__('filament.datasets_fake_news.article_info_description'))
                    ->icon('heroicon-m-document-text')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament.datasets_fake_news.article_title'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.datasets_fake_news.placeholders.title'))
                            ->columnSpan(2),

                        Textarea::make('content')
                            ->label(__('filament.datasets_fake_news.article_content'))
                            ->required()
                            ->rows(6)
                            ->placeholder(__('filament.datasets_fake_news.placeholders.content'))
                            ->columnSpanFull(),

                        TextInput::make('origin_dataset_name')
                            ->label(__('filament.datasets_fake_news.origin_dataset'))
                            ->placeholder(__('filament.datasets_fake_news.placeholders.origin_dataset'))
                            ->columnSpan(1),

                        TextInput::make('content_hash')
                            ->label(__('filament.datasets_fake_news.content_hash'))
                            ->required()
                            ->placeholder(__('filament.datasets_fake_news.placeholders.content_hash'))
                            ->helperText(__('filament.datasets_fake_news.helper_texts.content_hash'))
                            ->columnSpan(1),
                    ]),

                Section::make(__('filament.datasets_fake_news.detection_information'))
                    ->description(__('filament.datasets_fake_news.detection_info_description'))
                    ->icon('heroicon-m-cpu-chip')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('detected_at')
                            ->label(__('filament.datasets_fake_news.detected_at'))
                            ->required()
                            ->default(now())
                            ->columnSpan(1),

                        TextInput::make('confidence_score')
                            ->label(__('filament.datasets_fake_news.confidence_score'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->step(0.01)
                            ->minValue(0)
                            ->maxValue(1)
                            ->suffix('%')
                            ->helperText(__('filament.datasets_fake_news.helper_texts.confidence_score'))
                            ->columnSpan(1),

                        Toggle::make('added_by_ai')
                            ->label(__('filament.datasets_fake_news.added_by_ai'))
                            ->helperText(__('filament.datasets_fake_news.helper_texts.added_by_ai'))
                            ->default(true)
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
