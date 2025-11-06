<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserTypes: string implements HasColor, HasIcon, HasLabel
{
    case Guest = 'Guest';
    case Admin = 'Admin';

    public static function casesAsArray(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    public static function fromName(string $name): ?self
    {
        return self::tryFrom($name);
    }

    public static function fromValue(string|int|self $value): ?self
    {
        if ($value instanceof self) {
            return $value;
        }

        return self::tryFrom((string) $value);
    }

    public function getLabel(): string
    {
        return __('enums.UserTypes.'.(string) $this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Guest => 'success',
            self::Admin => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Guest => 'heroicon-m-user',
            self::Admin => 'heroicon-m-shield-check',
        };
    }
}
