<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
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

    public function findBySlug(string $slug): ?Post
    {
        $statement = $this->pdo->prepare(
            'SELECT id, title, slug, description, content, image, views, published_at
             FROM posts
             WHERE slug = :slug',
        );
        $statement->execute(['slug' => $slug]);
        $row = $statement->fetch();

        return $row === false ? null : $this->hydratePost($row);
    }

    public function incrementViews(string $slug): void
    {
        $statement = $this->pdo->prepare('UPDATE posts SET views = views + 1 WHERE slug = :slug');
        $statement->execute(['slug' => $slug]);
    }

    public function findSimilar(int $postId, int $limit): array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.id, p.title, p.slug, p.description, p.image, p.views, p.published_at,
                    COUNT(*) AS common_categories
             FROM post_category pc
             JOIN posts p ON p.id = pc.post_id
             WHERE pc.category_id IN (SELECT category_id FROM post_category WHERE post_id = :post_id)
               AND pc.post_id <> :exclude_id
             GROUP BY p.id
             ORDER BY common_categories DESC, p.published_at DESC, p.id DESC
             LIMIT :limit',
        );
        $statement->bindValue('post_id', $postId, PDO::PARAM_INT);
        $statement->bindValue('exclude_id', $postId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return array_map($this->hydrateSummary(...), $statement->fetchAll());
    }

    public function findLatest(int $limit, array $excludeIds = []): array
    {
        $where = $excludeIds === []
            ? ''
            : 'WHERE id NOT IN (' . implode(', ', array_fill(0, count($excludeIds), '?')) . ')';

        $statement = $this->pdo->prepare(sprintf(
            'SELECT id, title, slug, description, image, views, published_at
             FROM posts
             %s
             ORDER BY published_at DESC, id DESC
             LIMIT ?',
            $where,
        ));

        foreach ([...$excludeIds, $limit] as $index => $value) {
            $statement->bindValue($index + 1, $value, PDO::PARAM_INT);
        }

        $statement->execute();

        return array_map($this->hydrateSummary(...), $statement->fetchAll());
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

    /**
     * @param array<string, mixed> $row
     */
    private function hydratePost(array $row): Post
    {
        return new Post(
            (int) $row['id'],
            $row['title'],
            $row['slug'],
            $row['description'],
            $row['content'],
            $row['image'],
            (int) $row['views'],
            new DateTimeImmutable($row['published_at']),
        );
    }
}
