<?php

declare(strict_types=1);

namespace App\Seeder;

final readonly class PostText
{
    public function __construct(
        public string $title,
        public string $description,
        public string $content,
    ) {
    }
}
