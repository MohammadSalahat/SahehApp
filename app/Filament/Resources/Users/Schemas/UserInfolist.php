<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.users.user_profile'))
                    ->description(__('filament.users.user_profile_description'))
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('filament.users.name'))
                            ->icon('heroicon-o-user')
                            ->weight('bold')
                            ->copyable()
                            ->copyMessage(__('filament.users.name_copied')),

                        TextEntry::make('email')
                            ->label(__('filament.users.email'))
                            ->icon('heroicon-o-envelope')
                            ->copyable()
                            ->copyMessage(__('filament.users.email_copied'))
                            ->color('gray'),

                        TextEntry::make('role')
                            ->label(__('filament.users.user_role'))
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                User::ROLE_USER => 'info',
                                User::ROLE_ADMIN => 'warning',
                                default => 'gray',
                            })
                            ->icon(fn (string $state): string => match ($state) {
                                User::ROLE_USER => 'heroicon-o-user',
                                User::ROLE_ADMIN => 'heroicon-o-user-circle',
                                default => 'heroicon-o-user',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                User::ROLE_USER => __('filament.users.user'),
                                User::ROLE_ADMIN => __('filament.users.administrator'),
                                default => ucfirst($state),
                            }),
                    ])
                    ->columns(2),

                Section::make(__('filament.users.account_status'))
                    ->description(__('filament.users.account_status_description'))
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        TextEntry::make('email_verified_at')
                            ->label(__('filament.users.email_verification'))
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? __('filament.users.verified') : __('filament.users.unverified'))
                            ->color(fn ($state) => $state ? 'success' : 'danger')
                            ->icon(fn ($state) => $state ? 'heroicon-o-shield-check' : 'heroicon-o-shield-exclamation')
                            ->placeholder(__('filament.users.not_verified'))
                            ->dateTime('M j, Y \a\t g:i A')
                            ->suffix(fn ($state) => $state ? '' : ' - '.__('filament.users.email_verification_required')),

                        TextEntry::make('two_factor_confirmed_at')
                            ->label(__('filament.users.two_factor_authentication'))
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? __('filament.users.enabled') : __('filament.users.disabled'))
                            ->color(fn ($state) => $state ? 'success' : 'gray')
                            ->icon(fn ($state) => $state ? 'heroicon-o-shield-check' : 'heroicon-o-shield-exclamation')
                            ->placeholder(__('filament.users.not_enabled'))
                            ->dateTime('M j, Y \a\t g:i A')
                            ->suffix(fn ($state) => $state ? '' : ' - '.__('filament.users.two_factor_disabled')),
                    ])
                    ->columns(2),

                Section::make(__('filament.users.account_activity'))
                    ->description(__('filament.users.account_activity_description'))
                    ->icon('heroicon-o-clock')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.users.account_created'))
                            ->icon('heroicon-o-plus-circle')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->placeholder(__('filament.users.unknown'))
                            ->color('success')
                            ->suffix(fn ($record) => ' ('.$record->created_at?->diffForHumans().')'),

                        TextEntry::make('updated_at')
                            ->label(__('filament.users.last_updated'))
                            ->icon('heroicon-o-pencil-square')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->placeholder(__('filament.users.never_updated'))
                            ->color('info')
                            ->suffix(fn ($record) => ' ('.$record->updated_at?->diffForHumans().')'),

                        TextEntry::make('deleted_at')
                            ->label(__('filament.users.account_deleted'))
                            ->icon('heroicon-o-trash')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->color('danger')
                            ->visible(fn (User $record): bool => $record->trashed())
                            ->suffix(fn ($record) => $record->deleted_at ? ' ('.$record->deleted_at->diffForHumans().')' : ''),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }
}
