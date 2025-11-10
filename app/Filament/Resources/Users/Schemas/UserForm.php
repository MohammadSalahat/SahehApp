<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                Section::make(__('filament.users.user_information'))
                    ->description(__('filament.users.basic_info'))
                    ->icon('heroicon-o-user')
                    ->collapsible()
                    ->columnSpan(3)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.users.name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.users.placeholders.name'))
                            ->prefixIcon('heroicon-o-user')
                            ->autocomplete('name'),

                        TextInput::make('email')
                            ->label(__('filament.users.email'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder(__('filament.users.placeholders.email'))
                            ->prefixIcon('heroicon-o-envelope')
                            ->autocomplete('email')
                            ->validationMessages([
                                'unique' => __('This email address is already registered.'),
                            ]),

                        TextInput::make('password')
                            ->label(__('filament.users.password'))
                            ->password()
                            ->required(fn ($context) => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->minLength(8)
                            ->placeholder(__('filament.users.placeholders.password'))
                            ->prefixIcon('heroicon-o-key')
                            ->revealable()
                            ->helperText(__('filament.users.helpers.password_length')),

                        ToggleButtons::make('role')
                            ->label(__('filament.users.role'))
                            ->required()
                            ->default(User::ROLE_USER)
                            ->inline()
                            ->options([
                                User::ROLE_USER => __('User'),
                                User::ROLE_ADMIN => __('Administrator'),
                            ])
                            ->icons([
                                User::ROLE_USER => 'heroicon-o-user',
                                User::ROLE_ADMIN => 'heroicon-o-user-circle',
                            ])
                            ->colors([
                                User::ROLE_USER => 'info',
                                User::ROLE_ADMIN => 'warning',
                            ])
                            ->helperText(__('filament.users.helpers.select_role'))
                            ->grouped(),
                    ])
                    ->columns(2),

                Section::make(__('filament.users.date_information'))
                    ->icon('heroicon-o-calendar')
                    ->collapsible()
                    ->schema([
                        DateTimePicker::make('created_at')
                            ->disabled()
                            ->label(__('filament.users.created_at'))
                            ->prefixIcon('heroicon-o-shield-check')
                            ->displayFormat('M j, Y \a\t g:i A')
                            ->native(false),

                        DateTimePicker::make('email_verified_at')
                            ->disabled()
                            ->label(__('filament.users.email_verified_at'))
                            ->placeholder(__('Select verification date'))
                            ->prefixIcon('heroicon-o-shield-check')
                            ->displayFormat('M j, Y \a\t g:i A')
                            ->native(false),

                        DateTimePicker::make('deleted_at')
                            ->disabled()
                            ->label(__('filament.users.deleted_at'))
                            ->prefixIcon('heroicon-o-shield-check')
                            ->displayFormat('M j, Y \a\t g:i A')
                            ->native(false),
                    ]),
            ]);
    }
}
