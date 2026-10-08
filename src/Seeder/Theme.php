<?php

declare(strict_types=1);

namespace App\Seeder;

final readonly class Theme
{
    /**
     * @param list<string> $actions
     * @param list<string> $topics
     * @param list<string> $sentences
     */
    public function __construct(
        public string $name,
        public string $description,
        public array $actions,
        public array $topics,
        public array $sentences,
    ) {
    }
}
