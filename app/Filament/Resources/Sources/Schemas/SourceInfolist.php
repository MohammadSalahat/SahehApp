<?php

namespace App\Filament\Resources\Sources\Schemas;

use App\Models\Source;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SourceInfolist
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
                        TextEntry::make('name')
                            ->label(__('filament.sources.source_name'))
                            ->weight('bold')
                            ->icon('heroicon-m-building-office')
                            ->copyable()
                            ->copyMessage(__('filament.sources.source_name_copied'))
                            ->columnSpan(2),

                        TextEntry::make('url')
                            ->label(__('filament.sources.website_url'))
                            ->icon('heroicon-m-link')
                            ->copyable()
                            ->copyMessage(__('filament.sources.url_copied'))
                            ->url(fn (string $state): string => $state)
                            ->openUrlInNewTab()
                            ->columnSpan(2),

                        TextEntry::make('description')
                            ->label(__('filament.sources.description'))
                            ->placeholder(__('filament.sources.no_description'))
                            ->prose()
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('filament.sources.reliability_assessment'))
                    ->description(__('filament.sources.reliability_description'))
                    ->icon('heroicon-m-shield-check')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('reliability_score')
                            ->label(__('filament.sources.reliability_score'))
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

                        IconEntry::make('is_active')
                            ->label(__('filament.sources.monitoring_status'))
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger')
                            ->formatStateUsing(fn (bool $state): string => $state ? __('filament.sources.active_monitored') : __('filament.sources.inactive_not_monitored')
                            )
                            ->columnSpan(1),
                    ]),

                Section::make(__('filament.sources.timestamps'))
                    ->description(__('filament.sources.timestamps_description'))
                    ->icon('heroicon-m-calendar')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.sources.added_at'))
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-m-calendar-days')
                            ->columnSpan(1),

                        TextEntry::make('updated_at')
                            ->label(__('filament.sources.updated_at'))
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-m-pencil')
                            ->placeholder(__('filament.sources.never_updated'))
                            ->columnSpan(1),

                        TextEntry::make('deleted_at')
                            ->label(__('filament.sources.deleted_at'))
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-m-trash')
                            ->visible(fn (Source $record): bool => $record->trashed())
                            ->color('danger')
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
