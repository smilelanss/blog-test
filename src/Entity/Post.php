<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;

final readonly class Post
{
    public function __construct(
        public int $id,
        public string $title,
        public string $slug,
        public string $description,
        public string $content,
        public ?string $image,
        public int $views,
        public DateTimeImmutable $publishedAt,
    ) {
    }
}
