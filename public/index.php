<?php

declare(strict_types=1);

use App\Core\Http\Kernel;
use App\Core\Http\Request;

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../config/container.php';

$container->get(Kernel::class)
    ->handle(Request::fromGlobals())
    ->send();
