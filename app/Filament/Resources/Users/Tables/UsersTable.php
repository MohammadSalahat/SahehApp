<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.users.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->icon('heroicon-o-user')
                    ->copyable()
                    ->copyMessage(__('filament.users.name_copied'))
                    ->description(fn ($record) => $record->email),

                TextColumn::make('email')
                    ->label(__('filament.users.email'))
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->copyMessage(__('filament.users.email_copied'))
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('role')
                    ->label(__('filament.users.role'))
                    ->searchable()
                    ->sortable()
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

                TextColumn::make('email_verified_at')
                    ->label(__('filament.users.email_verified'))
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? __('filament.users.verified') : __('filament.users.unverified'))
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->icon(fn ($state) => $state ? 'heroicon-o-shield-check' : 'heroicon-o-shield-exclamation')
                    ->tooltip(fn ($record) => $record->email_verified_at
                        ? __('filament.users.verified_on', ['date' => $record->email_verified_at->format('M j, Y')])
                        : __('filament.users.email_not_verified')),

                TextColumn::make('created_at')
                    ->label(__('filament.users.created_at'))
                    ->dateTime('M j, Y \a\t g:i A')
                    ->sortable()
                    ->description(fn ($record) => $record->created_at->diffForHumans())
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('filament.users.updated_at'))
                    ->dateTime('M j, Y \a\t g:i A')
                    ->sortable()
                    ->description(fn ($record) => $record->updated_at->diffForHumans())
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label(__('filament.users.deleted_at'))
                    ->dateTime('M j, Y \a\t g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('danger'),

                TextColumn::make('two_factor_confirmed_at')
                    ->label(__('filament.users.two_factor'))
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn ($state) => $state ? __('filament.users.enabled') : __('filament.users.disabled'))
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->icon(fn ($state) => $state ? 'heroicon-o-shield-check' : 'heroicon-o-shield-exclamation')
                    ->tooltip(fn ($record) => $record->two_factor_confirmed_at
                        ? __('filament.users.two_factor_enabled')
                        : __('filament.users.two_factor_disabled')),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label(__('filament.users.deleted_users')),

                SelectFilter::make('role')
                    ->label(__('filament.users.role'))
                    ->options([
                        User::ROLE_USER => __('filament.users.user'),
                        User::ROLE_ADMIN => __('filament.users.administrator'),
                    ])
                    ->indicator('role'),

                Filter::make('verified')
                    ->label(__('filament.users.email_verified'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at'))
                    ->indicator('verified'),

                Filter::make('unverified')
                    ->label(__('filament.users.email_unverified'))
                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at'))
                    ->indicator('unverified'),

                Filter::make('two_factor_enabled')
                    ->label(__('filament.users.two_factor_filter'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('two_factor_confirmed_at'))
                    ->indicator('2fa'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('View'))
                    ->color('info'),
                EditAction::make()
                    ->label(__('Edit'))
                    ->color('warning'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('Delete Selected')),
                    ForceDeleteBulkAction::make()
                        ->label(__('Force Delete')),
                    RestoreBulkAction::make()
                        ->label(__('Restore Selected')),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-user-group')
            ->emptyStateHeading(__('filament.users.no_users'))
            ->emptyStateDescription(__('filament.users.no_users_description'))
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->defaultSort('created_at', 'desc');
    }
}
