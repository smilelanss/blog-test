<?php

declare(strict_types=1);

namespace App\Seeder;

final readonly class SeedOptions
{
    public function __construct(
        public int $categories,
        public int $posts,
        public bool $fresh,
    ) {
    }
}
