<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use PDO;

final readonly class PdoCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function findNonEmpty(): array
    {
        $rows = $this->pdo->query(
            'SELECT c.id, c.name, c.slug, c.description, COUNT(*) AS posts_count
             FROM categories c
             JOIN post_category pc ON pc.category_id = c.id
             GROUP BY c.id
             ORDER BY c.name',
        )->fetchAll();

        return array_map($this->hydrate(...), $rows);
    }

    public function findByPostId(int $postId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT c.id, c.name, c.slug, c.description
             FROM categories c
             JOIN post_category pc ON pc.category_id = c.id
             WHERE pc.post_id = :post_id
             ORDER BY c.name',
        );
        $statement->execute(['post_id' => $postId]);

        return array_map($this->hydrate(...), $statement->fetchAll());
    }

    public function findBySlug(string $slug): ?Category
    {
        $statement = $this->pdo->prepare('SELECT id, name, slug, description FROM categories WHERE slug = :slug');
        $statement->execute(['slug' => $slug]);
        $row = $statement->fetch();

        return $row === false ? null : $this->hydrate($row);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): Category
    {
        return new Category(
            (int) $row['id'],
            $row['name'],
            $row['slug'],
            $row['description'],
            isset($row['posts_count']) ? (int) $row['posts_count'] : null,
        );
    }
}
