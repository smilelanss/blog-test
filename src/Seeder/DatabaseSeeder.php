<?php

declare(strict_types=1);

namespace App\Seeder;

use InvalidArgumentException;
use PDO;
use RuntimeException;
use Throwable;

final readonly class DatabaseSeeder implements SeederInterface
{
    private const MIN_CATEGORIES = 2;
    private const TABLES = ['post_category', 'posts', 'categories'];

    /**
     * @param list<SeederInterface> $seeders
     */
    public function __construct(
        private PDO $pdo,
        private ThemeCatalog $themes,
        private array $seeders,
    ) {
    }

    public function run(SeedOptions $options): void
    {
        $this->validate($options);

        if ($options->fresh) {
            $this->truncate();
        } elseif (!$this->isEmpty()) {
            throw new RuntimeException('База не пуста, запустите сидер с --fresh.');
        }

        $this->pdo->beginTransaction();

        try {
            foreach ($this->seeders as $seeder) {
                $seeder->run($options);
            }

            $this->pdo->commit();
        } catch (Throwable $exception) {
            $this->pdo->rollBack();

            throw $exception;
        }
    }

    /**
     * @return list<string>
     */
    public function emptyCategories(): array
    {
        return $this->pdo->query(
            'SELECT c.name
             FROM categories c
             WHERE NOT EXISTS (SELECT 1 FROM post_category pc WHERE pc.category_id = c.id)
             ORDER BY c.id',
        )->fetchAll(PDO::FETCH_COLUMN);
    }

    private function validate(SeedOptions $options): void
    {
        if ($options->categories < self::MIN_CATEGORIES || $options->categories > count($this->themes)) {
            throw new InvalidArgumentException(sprintf(
                'Число категорий должно быть от %d до %d.',
                self::MIN_CATEGORIES,
                count($this->themes),
            ));
        }

        if ($options->posts < 1) {
            throw new InvalidArgumentException('Число статей должно быть больше нуля.');
        }
    }

    private function truncate(): void
    {
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

        try {
            foreach (self::TABLES as $table) {
                $this->pdo->exec('TRUNCATE TABLE ' . $table);
            }
        } finally {
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        }
    }

    private function isEmpty(): bool
    {
        return (int) $this->pdo->query(
            'SELECT (SELECT COUNT(*) FROM categories) + (SELECT COUNT(*) FROM posts)',
        )->fetchColumn() === 0;
    }
}
