<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);
});

return require __DIR__ . '/container.php';
