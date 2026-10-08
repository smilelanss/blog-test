<?php

declare(strict_types=1);

use App\Core\Http\Kernel;
use App\Core\Http\Request;

require __DIR__ . '/../vendor/autoload.php';

set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);
});

$container = require __DIR__ . '/../config/container.php';

$container->get(Kernel::class)
    ->handle(Request::fromGlobals())
    ->send();
