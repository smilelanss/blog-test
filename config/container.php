<?php

declare(strict_types=1);

use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Core\Container\Container;
use App\Core\Database\Migrator;
use App\Core\Database\PdoFactory;
use App\Core\Http\ErrorHandler;
use App\Core\Http\Kernel;
use App\Core\Log\FileLogger;
use App\Core\Routing\Router;
use App\Core\View\SmartyRenderer;
use App\Core\View\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

$settings = require __DIR__ . '/settings.php';

$container = new Container();

$container->set(PDO::class, fn () => PdoFactory::create($settings['db']));
$container->set(Migrator::class, fn (ContainerInterface $c) => new Migrator(
    $c->get(PDO::class),
    $settings['migrations_dir'],
));
$container->set(LoggerInterface::class, fn () => new FileLogger($settings['log_file']));
$container->set(TemplateRendererInterface::class, fn () => new SmartyRenderer(
    $settings['templates_dir'],
    $settings['smarty_compile_dir'],
));

$container->set(Router::class, fn () => new Router(require __DIR__ . '/routes.php'));
$container->set(ErrorHandler::class, fn (ContainerInterface $c) => new ErrorHandler(
    $c->get(TemplateRendererInterface::class),
    $c->get(LoggerInterface::class),
    $settings['debug'],
));
$container->set(Kernel::class, fn (ContainerInterface $c) => new Kernel(
    $c->get(Router::class),
    $c,
    $c->get(ErrorHandler::class),
));

$container->set(HomeController::class, fn (ContainerInterface $c) => new HomeController(
    $c->get(TemplateRendererInterface::class),
));
$container->set(CategoryController::class, fn (ContainerInterface $c) => new CategoryController(
    $c->get(TemplateRendererInterface::class),
));
$container->set(PostController::class, fn (ContainerInterface $c) => new PostController(
    $c->get(TemplateRendererInterface::class),
));

return $container;
