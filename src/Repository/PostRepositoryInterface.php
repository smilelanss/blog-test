<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PostSummary;

interface PostRepositoryInterface
{
    /**
     * @return array<int, list<PostSummary>>
     */
    public function findLatestGroupedByCategory(int $limit): array;
}
