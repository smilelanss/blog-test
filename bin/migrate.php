<?php

declare(strict_types=1);

use App\Core\Database\Migrator;

$container = require __DIR__ . '/../config/bootstrap.php';

try {
    $applied = $container->get(Migrator::class)->migrate();
} catch (Throwable $exception) {
    fwrite(STDERR, 'Ошибка миграции: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}

if ($applied === []) {
    echo 'Новых миграций нет.' . PHP_EOL;
}

foreach ($applied as $name) {
    echo 'Применена миграция ' . $name . PHP_EOL;
}
