<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostSummary;

interface PostRepositoryInterface
{
    /**
     * @return array<int, list<PostSummary>>
     */
    public function findLatestGroupedByCategory(int $limit): array;

    public function findBySlug(string $slug): ?Post;

    public function incrementViews(string $slug): void;

    /**
     * @return list<PostSummary>
     */
    public function findSimilar(int $postId, int $limit): array;

    /**
     * @param list<int> $excludeIds
     *
     * @return list<PostSummary>
     */
    public function findLatest(int $limit, array $excludeIds = []): array;
}
