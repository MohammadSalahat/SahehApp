<?php

namespace App\Filament\Resources\LatestNews\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LatestNewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make()
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament.labels.title'))
                            ->required()
                            ->columnSpanFull()
                            ->prefixIcon('heroicon-o-newspaper'),
                        Textarea::make('content')
                            ->label(__('filament.labels.content'))
                            ->required()
                            ->columnSpanFull()
                            ->rows(6),
                    ]),
                
                Section::make()
                    ->icon('heroicon-o-user')
                    ->collapsible()
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('author')
                            ->label(__('filament.labels.author'))
                            ->prefixIcon('heroicon-o-user-circle'),
                    ]),
                
                Section::make()
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->columnSpan(1)
                    ->schema([
                        FileUpload::make('image_url')
                            ->label(__('filament.labels.image'))
                            ->image()
                            ->imageEditor()
                            ->imagePreviewHeight('200'),
                    ]),
            ]);
    }
}
