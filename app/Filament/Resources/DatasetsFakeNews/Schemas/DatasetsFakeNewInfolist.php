<?php

namespace App\Filament\Resources\DatasetsFakeNews\Schemas;

use App\Models\DatasetsFakeNew;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DatasetsFakeNewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.datasets_fake_news.article_information'))
                    ->description(__('filament.datasets_fake_news.article_info_description'))
                    ->icon('heroicon-m-document-text')
                    ->schema([
                        TextEntry::make('title')
                            ->label(__('filament.datasets_fake_news.article_title'))
                            ->weight('bold')
                            ->copyable()
                            ->copyMessage(__('filament.datasets_fake_news.title_copied'))
                            ->columnSpanFull(),

                        TextEntry::make('content')
                            ->label(__('filament.datasets_fake_news.article_content'))
                            ->prose()
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('filament.datasets_fake_news.detection_information'))
                    ->description(__('filament.datasets_fake_news.detection_info_description'))
                    ->icon('heroicon-m-cpu-chip')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('confidence_score')
                            ->label(__('filament.datasets_fake_news.ai_confidence'))
                            ->badge()
                            ->color(fn (string $state): string => match (true) {
                                ((float) $state) >= 0.8 => 'success',
                                ((float) $state) >= 0.6 => 'warning',
                                ((float) $state) >= 0.4 => 'orange',
                                default => 'danger',
                            })
                            ->icon('heroicon-m-chart-bar')
                            ->formatStateUsing(fn (string $state): string => number_format((float) $state * 100, 1).'%'
                            )
                            ->columnSpan(1),

                        TextEntry::make('detected_at')
                            ->label(__('filament.datasets_fake_news.detection_date'))
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-m-calendar')
                            ->columnSpan(1),

                        IconEntry::make('added_by_ai')
                            ->label(__('filament.datasets_fake_news.detection_method'))
                            ->boolean()
                            ->trueIcon('heroicon-o-cpu-chip')
                            ->falseIcon('heroicon-o-user')
                            ->trueColor('info')
                            ->falseColor('gray')
                            ->formatStateUsing(fn (bool $state): string => $state ? __('filament.datasets_fake_news.automatically_detected') : __('filament.datasets_fake_news.manually_added')
                            )
                            ->columnSpan(2),
                    ]),

                Section::make(__('filament.datasets_fake_news.source_information'))
                    ->description(__('filament.datasets_fake_news.source_info_description'))
                    ->icon('heroicon-m-server-stack')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('origin_dataset_name')
                            ->label(__('filament.datasets_fake_news.origin_dataset'))
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-m-server-stack')
                            ->placeholder(__('filament.datasets_fake_news.unknown_dataset'))
                            ->columnSpan(1),

                        TextEntry::make('content_hash')
                            ->label(__('filament.datasets_fake_news.content_hash'))
                            ->icon('heroicon-m-hashtag')
                            ->copyable()
                            ->copyMessage(__('filament.datasets_fake_news.hash_copied'))
                            ->columnSpan(1),
                    ]),

                Section::make(__('filament.datasets_fake_news.timestamps'))
                    ->description(__('filament.datasets_fake_news.timestamps_description'))
                    ->icon('heroicon-m-calendar')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.datasets_fake_news.added_at'))
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-m-calendar-days')
                            ->columnSpan(1),

                        TextEntry::make('updated_at')
                            ->label(__('filament.datasets_fake_news.updated_at'))
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-m-pencil')
                            ->placeholder(__('filament.datasets_fake_news.never_updated'))
                            ->columnSpan(1),

                        TextEntry::make('deleted_at')
                            ->label(__('filament.datasets_fake_news.deleted_at'))
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-m-trash')
                            ->visible(fn (DatasetsFakeNew $record): bool => $record->trashed())
                            ->color('danger')
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
