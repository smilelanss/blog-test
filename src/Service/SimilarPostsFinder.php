<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Post;
use App\Entity\PostSummary;
use App\Repository\PostRepositoryInterface;

final readonly class SimilarPostsFinder
{
    public function __construct(
        private PostRepositoryInterface $posts,
    ) {
    }

    /**
     * @return list<PostSummary>
     */
    public function find(Post $post, int $limit = 3): array
    {
        $similar = $this->posts->findSimilar($post->id, $limit);
        $missing = $limit - count($similar);

        if ($missing <= 0) {
            return $similar;
        }

        $excludeIds = [$post->id, ...array_map(fn (PostSummary $summary): int => $summary->id, $similar)];

        return [...$similar, ...$this->posts->findLatest($missing, $excludeIds)];
    }
}
