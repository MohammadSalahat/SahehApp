<?php

namespace App\Filament\Resources\ContactRequests\Schemas;

use App\Enums\RequestStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Client Information')
                            ->schema([
                                ToggleButtons::make('status')
                                    ->inline()
                                    ->label('Status')
                                    ->options(RequestStatus::class)
                                    ->default(RequestStatus::New->value)
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('full_name')
                                    ->required()
                                    ->readOnly(),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->readOnly(),
                                Textarea::make('message')
                                    ->columnSpanFull()
                                    ->readOnly(),
                            ])->columns(2),
                    ])->columnSpan(3),
                Section::make('Follow Up Details')
                    ->schema([
                        DateTimePicker::make('created_at')
                            ->disabled()
                            ->label('Created At')
                            ->hint('When this request was created'),
                        DateTimePicker::make('last_contacted_at')
                            ->label('Last Contact Date')
                            ->hint('When you last contacted this client'),

                        Textarea::make('notes')
                            ->helperText('Add any notes or comments about this request')
                            ->columnSpanFull(),
                    ])->columnSpan(2),
            ])->columns(5);
    }
}
