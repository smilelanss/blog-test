<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostSummary;
use App\Enum\PostSort;

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

    public function countByCategory(int $categoryId): int;

    /**
     * @return list<PostSummary>
     */
    public function findByCategory(int $categoryId, PostSort $sort, int $limit, int $offset): array;
}
