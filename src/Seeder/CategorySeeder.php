<?php

declare(strict_types=1);

namespace App\Seeder;

use App\Support\Slugger;
use PDO;

final readonly class CategorySeeder implements SeederInterface
{
    public function __construct(
        private PDO $pdo,
        private ThemeCatalog $themes,
        private Slugger $slugger,
    ) {
    }

    public function run(SeedOptions $options): void
    {
        $insert = $this->pdo->prepare(
            'INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)',
        );

        foreach ($this->themes->first($options->categories) as $theme) {
            $insert->execute([
                'name' => $theme->name,
                'slug' => $this->slugger->slugify($theme->name),
                'description' => $theme->description,
            ]);
        }
    }
}
