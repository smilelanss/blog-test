<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;

final readonly class Pagination
{
    private const WINDOW = 2;

    public function __construct(
        public int $total,
        public int $perPage,
        public int $page,
    ) {
        if ($total < 0 || $perPage < 1 || $page < 1) {
            throw new InvalidArgumentException(sprintf(
                'Некорректные параметры пагинации: total=%d, perPage=%d, page=%d.',
                $total,
                $perPage,
                $page,
            ));
        }
    }

    public function totalPages(): int
    {
        return max(1, (int) ceil($this->total / $this->perPage));
    }

    public function offset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }

    public function hasPrevious(): bool
    {
        return $this->page > 1;
    }

    public function hasNext(): bool
    {
        return $this->page < $this->totalPages();
    }

    /**
     * @return list<int|null>
     */
    public function pages(): array
    {
        $last = $this->totalPages();
        $current = min($this->page, $last);

        $visible = array_unique([
            1,
            ...range(max(1, $current - self::WINDOW), min($last, $current + self::WINDOW)),
            $last,
        ]);
        sort($visible);

        $pages = [];
        $previous = 0;

        foreach ($visible as $page) {
            if ($page - $previous === 2) {
                $pages[] = $page - 1;
            } elseif ($page - $previous > 2) {
                $pages[] = null;
            }

            $pages[] = $page;
            $previous = $page;
        }

        return $pages;
    }
}
