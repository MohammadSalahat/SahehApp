<?php

namespace App\Filament\Resources\ContactRequests\Tables;

use App\Enums\RequestStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label(__('filament.contact_requests.full_name'))
                    ->weight(FontWeight::Bold)
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-m-user')
                    ->copyable()
                    ->copyMessage(__('filament.contact_requests.name_copied')),

                TextColumn::make('email')
                    ->label(__('filament.contact_requests.email_address'))
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage(__('filament.contact_requests.email_copied')),

                TextColumn::make('status')
                    ->label(__('filament.contact_requests.status'))
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->color(fn (RequestStatus $state): string => match ($state) {
                        RequestStatus::New => 'success',
                        RequestStatus::Read => 'warning',
                        RequestStatus::Responded => 'info',
                        RequestStatus::Archived => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn (RequestStatus $state): string => match ($state) {
                        RequestStatus::New => 'heroicon-m-sparkles',
                        RequestStatus::Read => 'heroicon-m-eye',
                        RequestStatus::Responded => 'heroicon-m-check-circle',
                        RequestStatus::Archived => 'heroicon-m-archive-box',
                        default => 'heroicon-m-minus-circle',
                    }),

                TextColumn::make('message')
                    ->label(__('filament.contact_requests.message'))
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 50 ? $state : null;
                    })
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->searchable()
                    ->placeholder(__('filament.contact_requests.no_message')),

                TextColumn::make('last_contacted_at')
                    ->label(__('filament.contact_requests.last_contact_date'))
                    ->dateTime()
                    ->since()
                    ->icon('heroicon-m-phone')
                    ->color('info')
                    ->placeholder(__('filament.contact_requests.never_contacted'))
                    ->toggleable(),

                TextColumn::make('notes')
                    ->label(__('filament.contact_requests.notes'))
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 30 ? $state : null;
                    })
                    ->icon('heroicon-m-document-text')
                    ->placeholder(__('filament.contact_requests.no_notes'))
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('filament.contact_requests.submitted_at'))
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->icon('heroicon-m-calendar')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->label(__('filament.contact_requests.updated_at'))
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->icon('heroicon-m-pencil')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.contact_requests.request_status'))
                    ->options(RequestStatus::class)
                    ->multiple(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make()
                    ->color('info')
                    ->icon('heroicon-m-eye'),
                EditAction::make()
                    ->color('warning')
                    ->icon('heroicon-m-pencil'),
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-m-trash'),
                ]),
            ])
            ->emptyStateHeading(__('filament.contact_requests.no_contact_requests'))
            ->emptyStateDescription(__('filament.contact_requests.no_contact_requests_description'))
            ->emptyStateIcon('heroicon-o-inbox-arrow-down');
    }
}
