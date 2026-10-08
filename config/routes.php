<?php

declare(strict_types=1);

use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Core\Routing\Route;

return [
    new Route('GET', '/', HomeController::class),
    new Route('GET', '/category/{slug}', CategoryController::class),
    new Route('GET', '/post/{slug}', PostController::class),
];
