<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PostSummary;
use DateTimeImmutable;
use PDO;

final readonly class PdoPostRepository implements PostRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function findLatestGroupedByCategory(int $limit): array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, title, slug, description, image, views, published_at, category_id
             FROM (
                 SELECT p.id, p.title, p.slug, p.description, p.image, p.views, p.published_at,
                        pc.category_id,
                        ROW_NUMBER() OVER (
                            PARTITION BY pc.category_id
                            ORDER BY p.published_at DESC, p.id DESC
                        ) AS rn
                 FROM post_category pc
                 JOIN posts p ON p.id = pc.post_id
             ) ranked
             WHERE rn <= :limit
             ORDER BY category_id, rn',
        );
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $grouped = [];

        foreach ($statement->fetchAll() as $row) {
            $grouped[(int) $row['category_id']][] = $this->hydrateSummary($row);
        }

        return $grouped;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function hydrateSummary(array $row): PostSummary
    {
        return new PostSummary(
            (int) $row['id'],
            $row['title'],
            $row['slug'],
            $row['description'],
            $row['image'],
            (int) $row['views'],
            new DateTimeImmutable($row['published_at']),
        );
    }
}
