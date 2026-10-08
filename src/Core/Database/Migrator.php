<?php

declare(strict_types=1);

namespace App\Core\Database;

use PDO;

final readonly class Migrator
{
    public function __construct(
        private PDO $pdo,
        private string $migrationsDir,
    ) {
    }

    /**
     * @return list<string>
     */
    public function migrate(): array
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS migrations (
                name       VARCHAR(255) NOT NULL PRIMARY KEY,
                applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
        );

        $applied = $this->pdo->query('SELECT name FROM migrations')->fetchAll(PDO::FETCH_COLUMN);

        $files = glob($this->migrationsDir . '/*.sql') ?: [];
        sort($files);

        $insert = $this->pdo->prepare('INSERT INTO migrations (name) VALUES (:name)');
        $new = [];

        foreach ($files as $file) {
            $name = basename($file);

            if (in_array($name, $applied, true)) {
                continue;
            }

            $this->pdo->exec(file_get_contents($file));
            $insert->execute(['name' => $name]);
            $new[] = $name;
        }

        return $new;
    }
}
