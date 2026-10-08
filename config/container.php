<?php

declare(strict_types=1);

use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Core\Container\Container;
use App\Core\Http\Kernel;
use App\Core\Routing\Router;
use Psr\Container\ContainerInterface;

$container = new Container();

$container->set(Router::class, fn () => new Router(require __DIR__ . '/routes.php'));
$container->set(Kernel::class, fn (ContainerInterface $c) => new Kernel($c->get(Router::class), $c));

$container->set(HomeController::class, fn () => new HomeController());
$container->set(CategoryController::class, fn () => new CategoryController());
$container->set(PostController::class, fn () => new PostController());

return $container;
