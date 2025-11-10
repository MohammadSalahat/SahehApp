<?php

namespace App\Filament\Resources\Feedback\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeedbackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.feedback.feedback_information'))
                    ->description(__('filament.feedback.feedback_info_description'))
                    ->icon('heroicon-m-information-circle')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label(__('filament.feedback.user'))
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder(__('filament.feedback.placeholders.user'))
                            ->columnSpan(1),

                        TextInput::make('article_title')
                            ->label(__('filament.feedback.article_title'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.feedback.placeholders.article_title'))
                            ->columnSpan(1),

                        Select::make('rating')
                            ->label(__('filament.feedback.rating'))
                            ->required()
                            ->options([
                                1 => '⭐ 1 - '.__('filament.feedback.ratings.poor'),
                                2 => '⭐⭐ 2 - '.__('filament.feedback.ratings.fair'),
                                3 => '⭐⭐⭐ 3 - '.__('filament.feedback.ratings.good'),
                                4 => '⭐⭐⭐⭐ 4 - '.__('filament.feedback.ratings.very_good'),
                                5 => '⭐⭐⭐⭐⭐ 5 - '.__('filament.feedback.ratings.excellent'),
                            ])
                            ->placeholder(__('filament.feedback.placeholders.rating'))
                            ->columnSpan(1),

                        Select::make('verification_result')
                            ->label(__('filament.feedback.verification_result'))
                            ->options([
                                'real' => '✅ '.__('filament.feedback.real'),
                                'fake' => '❌ '.__('filament.feedback.fake'),
                                'uncertain' => '❓ '.__('filament.feedback.uncertain'),
                                'pending' => '⏳ '.__('filament.feedback.pending'),
                            ])
                            ->placeholder(__('filament.feedback.placeholders.verification'))
                            ->columnSpan(1),
                    ]),

                Section::make(__('filament.feedback.feedback_message'))
                    ->description(__('filament.feedback.feedback_message_description'))
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->schema([
                        Textarea::make('message')
                            ->label(__('filament.feedback.message'))
                            ->rows(4)
                            ->placeholder(__('filament.feedback.placeholders.message'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
