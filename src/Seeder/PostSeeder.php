<?php

declare(strict_types=1);

namespace App\Seeder;

use App\Support\Slugger;
use PDO;
use Random\Randomizer;

final readonly class PostSeeder implements SeederInterface
{
    private const MAX_CATEGORIES_PER_POST = 3;
    private const MAX_VIEWS = 5000;
    private const MAX_AGE_SECONDS = 365 * 24 * 60 * 60;

    /**
     * @param list<string> $images
     */
    public function __construct(
        private PDO $pdo,
        private ThemeCatalog $themes,
        private TextGenerator $texts,
        private Slugger $slugger,
        private Randomizer $random,
        private array $images,
    ) {
    }

    public function run(SeedOptions $options): void
    {
        $categories = $this->pdo->query('SELECT id, name FROM categories ORDER BY id')->fetchAll();
        array_pop($categories);

        $insertPost = $this->pdo->prepare(
            'INSERT INTO posts (title, slug, description, content, image, views, published_at)
             VALUES (:title, :slug, :description, :content, :image, :views, :published_at)',
        );
        $insertLink = $this->pdo->prepare(
            'INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)',
        );
        $usedSlugs = [];

        for ($i = 0; $i < $options->posts; $i++) {
            $count = $this->random->getInt(1, min(self::MAX_CATEGORIES_PER_POST, count($categories)));
            $picked = $this->random->shuffleArray($this->random->pickArrayKeys($categories, $count));
            $text = $this->texts->generate($this->themes->get($categories[$picked[0]]['name']));

            $insertPost->execute([
                'title' => $text->title,
                'slug' => $this->uniqueSlug($text->title, $usedSlugs),
                'description' => $text->description,
                'content' => $text->content,
                'image' => $this->pickImage(),
                'views' => $this->random->getInt(0, self::MAX_VIEWS),
                'published_at' => date('Y-m-d H:i:s', time() - $this->random->getInt(0, self::MAX_AGE_SECONDS)),
            ]);
            $postId = (int) $this->pdo->lastInsertId();

            foreach ($picked as $key) {
                $insertLink->execute(['post_id' => $postId, 'category_id' => $categories[$key]['id']]);
            }
        }
    }

    private function pickImage(): ?string
    {
        if ($this->images === []) {
            return null;
        }

        return $this->images[$this->random->getInt(0, count($this->images) - 1)];
    }

    /**
     * @param array<string, true> $used
     */
    private function uniqueSlug(string $title, array &$used): string
    {
        $base = $this->slugger->slugify($title);
        $slug = $base;

        for ($suffix = 2; isset($used[$slug]); $suffix++) {
            $slug = $base . '-' . $suffix;
        }

        $used[$slug] = true;

        return $slug;
    }
}
