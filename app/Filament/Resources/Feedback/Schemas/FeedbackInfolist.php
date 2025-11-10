<?php

namespace App\Filament\Resources\Feedback\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeedbackInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.feedback.user_information'))
                    ->description(__('filament.feedback.user_info_description'))
                    ->icon('heroicon-m-user')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label(__('filament.feedback.user_name'))
                            ->icon('heroicon-m-user')
                            ->copyable()
                            ->copyMessage(__('filament.feedback.name_copied'))
                            ->columnSpan(1),

                        TextEntry::make('user.email')
                            ->label(__('filament.feedback.user_email'))
                            ->icon('heroicon-m-envelope')
                            ->copyable()
                            ->copyMessage(__('filament.feedback.email_copied'))
                            ->columnSpan(1),
                    ]),

                Section::make(__('filament.feedback.article_rating_information'))
                    ->description(__('filament.feedback.article_rating_description'))
                    ->icon('heroicon-m-star')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('article_title')
                            ->label(__('filament.feedback.article_title'))
                            ->icon('heroicon-m-document-text')
                            ->copyable()
                            ->copyMessage(__('filament.feedback.title_copied'))
                            ->columnSpan(2),

                        TextEntry::make('rating')
                            ->label(__('filament.feedback.user_rating'))
                            ->icon('heroicon-m-star')
                            ->badge()
                            ->color(fn (string $state): string => match ((int) $state) {
                                1 => 'danger',
                                2 => 'orange',
                                3 => 'yellow',
                                4 => 'info',
                                5 => 'success',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => str_repeat('⭐', (int) $state)." ({$state}/5)"
                            )
                            ->columnSpan(1),

                        TextEntry::make('verification_result')
                            ->label(__('filament.feedback.verification_status'))
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
                                'real' => '✅ ' . __('filament.feedback.real'),
                                'fake' => '❌ ' . __('filament.feedback.fake'),
                                'uncertain' => '❓ ' . __('filament.feedback.uncertain'),
                                'pending' => '⏳ ' . __('filament.feedback.pending'),
                                default => ucfirst($state),
                            })
                            ->placeholder(__('filament.feedback.not_verified'))
                            ->columnSpan(1),
                    ]),

                Section::make(__('filament.feedback.feedback_message'))
                    ->description(__('filament.feedback.feedback_message_description'))
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->schema([
                        TextEntry::make('message')
                            ->label(__('filament.feedback.user_message'))
                            ->placeholder(__('filament.feedback.no_message_provided'))
                            ->columnSpanFull()
                            ->prose()
                            ->markdown(),
                    ]),

                Section::make(__('filament.feedback.timestamps'))
                    ->description(__('filament.feedback.timestamps_description'))
                    ->icon('heroicon-m-calendar')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.feedback.submitted_at'))
                            ->dateTime()
                            ->icon('heroicon-m-calendar-days')
                            ->since()
                            ->placeholder(__('filament.feedback.unknown'))
                            ->columnSpan(1),

                        TextEntry::make('updated_at')
                            ->label(__('filament.feedback.updated_at'))
                            ->dateTime()
                            ->icon('heroicon-m-pencil')
                            ->since()
                            ->placeholder(__('filament.feedback.never_updated'))
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
