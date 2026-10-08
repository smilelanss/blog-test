<?php

declare(strict_types=1);

use App\Seeder\DatabaseSeeder;
use App\Seeder\SeedOptions;

$container = require __DIR__ . '/../config/bootstrap.php';

$args = getopt('', ['categories:', 'posts:', 'fresh']);

$count = static function (string $name, int $default) use ($args): int {
    if (!isset($args[$name])) {
        return $default;
    }

    $value = filter_var($args[$name], FILTER_VALIDATE_INT);

    if ($value === false) {
        throw new InvalidArgumentException(sprintf('Значение --%s должно быть целым числом.', $name));
    }

    return $value;
};

try {
    $options = new SeedOptions($count('categories', 8), $count('posts', 60), isset($args['fresh']));
    $seeder = $container->get(DatabaseSeeder::class);
    $seeder->run($options);
} catch (Throwable $exception) {
    fwrite(STDERR, 'Ошибка сидинга: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}

echo sprintf('Создано категорий: %d, статей: %d.', $options->categories, $options->posts) . PHP_EOL;
echo 'Пустая категория: ' . implode(', ', $seeder->emptyCategories()) . PHP_EOL;
