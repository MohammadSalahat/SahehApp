<?php

namespace App\Filament\Resources\Feedback\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FeedbackTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('filament.feedback.user'))
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-user')
                    ->description(fn ($record) => $record->user->email ?? 'N/A'),

                TextColumn::make('article_title')
                    ->label(__('filament.feedback.article_title'))
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 50 ? $state : null;
                    })
                    ->icon('heroicon-m-document-text'),

                TextColumn::make('rating')
                    ->label(__('filament.feedback.rating'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ((int) $state) {
                        1 => 'danger',
                        2 => 'warning',
                        3 => 'gray',
                        4 => 'info',
                        5 => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str_repeat('⭐', (int) $state)." ({$state}/5)"
                    ),

                TextColumn::make('verification_result')
                    ->label(__('filament.feedback.verification'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'real' => 'success',
                        'fake' => 'danger',
                        'uncertain' => 'warning',
                        'pending' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'real' => 'heroicon-m-check-circle',
                        'fake' => 'heroicon-m-x-circle',
                        'uncertain' => 'heroicon-m-question-mark-circle',
                        'pending' => 'heroicon-m-clock',
                        default => 'heroicon-m-minus-circle',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'real' => '✅ '.__('filament.feedback.real'),
                        'fake' => '❌ '.__('filament.feedback.fake'),
                        'uncertain' => '❓ '.__('filament.feedback.uncertain'),
                        'pending' => '⏳ '.__('filament.feedback.pending'),
                        default => ucfirst($state),
                    }),

                TextColumn::make('message')
                    ->label(__('filament.feedback.message'))
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 30 ? $state : null;
                    })
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->placeholder(__('filament.feedback.no_message')),

                TextColumn::make('created_at')
                    ->label(__('filament.feedback.submitted_at'))
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-m-calendar')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->label(__('filament.feedback.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-m-pencil')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('rating')
                    ->label(__('filament.feedback.rating'))
                    ->options([
                        1 => '⭐ 1 '.__('filament.feedback.ratings.star'),
                        2 => '⭐⭐ 2 '.__('filament.feedback.ratings.stars'),
                        3 => '⭐⭐⭐ 3 '.__('filament.feedback.ratings.stars'),
                        4 => '⭐⭐⭐⭐ 4 '.__('filament.feedback.ratings.stars'),
                        5 => '⭐⭐⭐⭐⭐ 5 '.__('filament.feedback.ratings.stars'),
                    ])
                    ->multiple(),

                SelectFilter::make('verification_result')
                    ->label(__('filament.feedback.verification_status'))
                    ->options([
                        'real' => '✅ '.__('filament.feedback.real'),
                        'fake' => '❌ '.__('filament.feedback.fake'),
                        'uncertain' => '❓ '.__('filament.feedback.uncertain'),
                        'pending' => '⏳ '.__('filament.feedback.pending'),
                    ])
                    ->multiple(),

                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
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
                ]),
            ])
            ->emptyStateHeading(__('filament.feedback.no_feedback'))
            ->emptyStateDescription(__('filament.feedback.no_feedback_description'))
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right');
    }
}
