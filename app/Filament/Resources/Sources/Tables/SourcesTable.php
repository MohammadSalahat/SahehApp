<?php

namespace App\Filament\Resources\Sources\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SourcesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.sources.source_name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-globe-alt')
                    ->copyable()
                    ->copyMessage(__('filament.sources.source_name_copied')),

                TextColumn::make('url')
                    ->label(__('filament.sources.website_url'))
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 50 ? $state : null;
                    })
                    ->icon('heroicon-m-link')
                    ->copyable()
                    ->copyMessage(__('filament.sources.url_copied'))
                    ->url(fn (string $state): string => $state)
                    ->openUrlInNewTab(),

                TextColumn::make('reliability_score')
                    ->label(__('filament.sources.reliability'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        ((float) $state) >= 0.8 => 'success',
                        ((float) $state) >= 0.6 => 'warning',
                        ((float) $state) >= 0.4 => 'orange',
                        default => 'danger',
                    })
                    ->icon('heroicon-m-shield-check')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state * 100, 1).'%'
                    ),

                IconColumn::make('is_active')
                    ->label(__('filament.sources.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn (bool $state): string => $state ? __('filament.sources.active_monitored') : __('filament.sources.inactive_not_monitored')
                    ),

                TextColumn::make('description')
                    ->label(__('filament.sources.description'))
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 40 ? $state : null;
                    })
                    ->icon('heroicon-m-document-text')
                    ->placeholder(__('filament.sources.no_description'))
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('filament.sources.added_at'))
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->icon('heroicon-m-calendar-days')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('filament.sources.updated_at'))
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->icon('heroicon-m-pencil')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label(__('filament.sources.deleted_at'))
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('is_active')
                    ->label(__('filament.sources.source_status'))
                    ->options([
                        '1' => __('filament.sources.active_sources'),
                        '0' => __('filament.sources.inactive_sources'),
                    ]),

                Filter::make('high_reliability')
                    ->label(__('filament.sources.high_reliability'))
                    ->query(fn (Builder $query): Builder => $query->where('reliability_score', '>', 0.8)
                    ),

                Filter::make('low_reliability')
                    ->label(__('filament.sources.low_reliability'))
                    ->query(fn (Builder $query): Builder => $query->where('reliability_score', '<', 0.4)
                    ),
            ])
            ->defaultSort('reliability_score', 'desc')
            ->recordActions([
                ViewAction::make()
                    ->color('info')
                    ->icon('heroicon-m-eye'),
                EditAction::make()
                    ->color('warning')
                    ->icon('heroicon-m-pencil'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-m-trash'),
                    ForceDeleteBulkAction::make()
                        ->icon('heroicon-m-x-mark'),
                    RestoreBulkAction::make()
                        ->icon('heroicon-m-arrow-path'),
                ]),
            ])
            ->emptyStateHeading(__('filament.sources.no_sources'))
            ->emptyStateDescription(__('filament.sources.no_sources_description'))
            ->emptyStateIcon('heroicon-o-globe-alt');
    }
}
