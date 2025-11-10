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
                        Section::make(__('filament.contact_requests.contact_information'))
                            ->schema([
                                ToggleButtons::make('status')
                                    ->inline()
                                    ->label(__('filament.contact_requests.status'))
                                    ->options(RequestStatus::class)
                                    ->default(RequestStatus::New->value)
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('full_name')
                                    ->label(__('filament.contact_requests.full_name'))
                                    ->required()
                                    ->readOnly(),
                                TextInput::make('email')
                                    ->label(__('filament.contact_requests.email_address'))
                                    ->email()
                                    ->required()
                                    ->readOnly(),
                                Textarea::make('message')
                                    ->label(__('filament.contact_requests.message'))
                                    ->columnSpanFull()
                                    ->readOnly(),
                            ])->columns(2),
                    ])->columnSpan(3),
                Section::make(__('filament.contact_requests.followup_information'))
                    ->schema([
                        DateTimePicker::make('created_at')
                            ->disabled()
                            ->label(__('filament.contact_requests.created_at'))
                            ->hint(__('filament.contact_requests.helper_texts.created_at')),
                        DateTimePicker::make('last_contacted_at')
                            ->label(__('filament.contact_requests.last_contact_date'))
                            ->hint(__('filament.contact_requests.helper_texts.last_contacted_at')),

                        Textarea::make('notes')
                            ->label(__('filament.contact_requests.notes'))
                            ->helperText(__('filament.contact_requests.helper_texts.notes'))
                            ->columnSpanFull(),
                    ])->columnSpan(2),
            ])->columns(5);
    }
}
