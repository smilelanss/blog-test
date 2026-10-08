<?php

declare(strict_types=1);

namespace App\Seeder;

use Countable;
use InvalidArgumentException;

final readonly class ThemeCatalog implements Countable
{
    /**
     * @param list<Theme> $themes
     */
    public function __construct(
        private array $themes,
    ) {
    }

    public function count(): int
    {
        return count($this->themes);
    }

    /**
     * @return list<Theme>
     */
    public function first(int $count): array
    {
        return array_slice($this->themes, 0, $count);
    }

    public function get(string $name): Theme
    {
        foreach ($this->themes as $theme) {
            if ($theme->name === $name) {
                return $theme;
            }
        }

        throw new InvalidArgumentException(sprintf('Тема «%s» не найдена.', $name));
    }
}
