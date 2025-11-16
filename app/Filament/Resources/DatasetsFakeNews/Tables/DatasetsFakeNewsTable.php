<?php

namespace App\Filament\Resources\DatasetsFakeNews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DatasetsFakeNewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament.datasets_fake_news.article_title'))
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 50 ? $state : null;
                    })
                    ->icon('heroicon-m-document-text')
                    ->copyable()
                    ->copyMessage(__('filament.datasets_fake_news.title_copied')),

                TextColumn::make('content')
                    ->label(__('filament.datasets_fake_news.article_content'))
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->copyMessage(__('filament.datasets_fake_news.content_copied')),

                TextColumn::make('detected_at')
                    ->label(__('filament.datasets_fake_news.detected_at'))
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('confidence_score')
                    ->label(__('filament.datasets_fake_news.confidence_score'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 0.8 ? 'success' : ($state > 0.5 ? 'warning' : 'danger'))
                    ->formatStateUsing(fn ($state) => round($state * 100).'%')
                    ->icon('heroicon-m-shield-check'),

                TextColumn::make('origin_dataset_name')
                    ->label(__('filament.datasets_fake_news.origin_dataset'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-archive-box'),

                IconColumn::make('added_by_ai')
                    ->label(__('filament.datasets_fake_news.ai_detected'))
                    ->boolean()
                    ->trueIcon('heroicon-o-cpu-chip')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('info')
                    ->falseColor('gray'),

                TextColumn::make('content_hash')
                    ->label(__('filament.datasets_fake_news.content_hash'))
                    ->searchable()
                    ->limit(10)
                    ->tooltip(function (TextColumn $column): ?string {
                        return $column->getState();
                    })
                    ->copyable()
                    ->copyMessage(__('filament.datasets_fake_news.hash_copied'))
                    ->toggleable()
                    ->icon('heroicon-m-hashtag'),

                TextColumn::make('created_at')
                    ->label(__('filament.datasets_fake_news.added_at'))
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->icon('heroicon-m-calendar-days')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('filament.datasets_fake_news.updated_at'))
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->icon('heroicon-m-pencil')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label(__('filament.datasets_fake_news.deleted_at'))
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->icon('heroicon-m-trash')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('detected_at', 'desc')
            ->emptyStateHeading(__('filament.datasets_fake_news.no_fake_news_detected'))
            ->emptyStateDescription(__('filament.datasets_fake_news.no_fake_news_description'))
            ->emptyStateIcon('heroicon-o-shield-check');
    }
}
