<?php

namespace App\Filament\Resources\ContactRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.contact_requests.contact_information'))
                    ->description(__('filament.contact_requests.contact_info_description'))
                    ->icon('heroicon-m-user')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('full_name')
                            ->label(__('filament.contact_requests.full_name'))
                            ->icon('heroicon-m-user')
                            ->copyable()
                            ->copyMessage(__('filament.contact_requests.name_copied'))
                            ->weight('bold')
                            ->columnSpan(1),

                        TextEntry::make('email')
                            ->label(__('filament.contact_requests.email_address'))
                            ->icon('heroicon-m-envelope')
                            ->copyable()
                            ->copyMessage(__('filament.contact_requests.email_copied'))
                            ->columnSpan(1),

                        TextEntry::make('status')
                            ->label(__('filament.contact_requests.request_status'))
                            ->badge()
                            ->color(fn ($state) => $state->getColor())
                            ->icon(fn ($state) => $state->getIcon())
                            ->columnSpan(2),
                    ]),

                Section::make(__('filament.contact_requests.request_message'))
                    ->description(__('filament.contact_requests.request_message_description'))
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->schema([
                        TextEntry::make('message')
                            ->label(__('filament.contact_requests.client_message'))
                            ->placeholder(__('filament.contact_requests.no_message_provided'))
                            ->columnSpanFull()
                            ->prose()
                            ->markdown(),
                    ]),

                Section::make(__('filament.contact_requests.followup_information'))
                    ->description(__('filament.contact_requests.followup_info_description'))
                    ->icon('heroicon-m-document-text')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('last_contacted_at')
                            ->label(__('filament.contact_requests.last_contact_date'))
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-m-phone')
                            ->placeholder(__('filament.contact_requests.never_contacted'))
                            ->columnSpan(1),

                        TextEntry::make('notes')
                            ->label(__('filament.contact_requests.internal_notes'))
                            ->placeholder(__('filament.contact_requests.no_notes'))
                            ->prose()
                            ->markdown()
                            ->columnSpan(2),
                    ]),

                Section::make(__('filament.contact_requests.timestamps'))
                    ->description(__('filament.contact_requests.timestamps_description'))
                    ->icon('heroicon-m-calendar')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.contact_requests.submitted_at'))
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-m-calendar-days')
                            ->columnSpan(1),

                        TextEntry::make('updated_at')
                            ->label(__('filament.contact_requests.updated_at'))
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-m-pencil')
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
