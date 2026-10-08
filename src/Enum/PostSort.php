<?php

declare(strict_types=1);

namespace App\Enum;

enum PostSort: string
{
    case Date = 'date';
    case Views = 'views';

    public static function default(): self
    {
        return self::Date;
    }

    public function isDefault(): bool
    {
        return $this === self::default();
    }

    public function label(): string
    {
        return match ($this) {
            self::Date => 'Сначала новые',
            self::Views => 'Сначала популярные',
        };
    }
}
