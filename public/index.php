<?php

declare(strict_types=1);

use App\Core\Http\Kernel;
use App\Core\Http\Request;

$container = require __DIR__ . '/../config/bootstrap.php';

$container->get(Kernel::class)
    ->handle(Request::fromGlobals())
    ->send();
