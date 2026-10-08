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
