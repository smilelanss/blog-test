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
use App\Repository\CategoryRepositoryInterface;
use App\Repository\PdoCategoryRepository;
use App\Repository\PdoPostRepository;
use App\Repository\PostRepositoryInterface;
use App\Seeder\CategorySeeder;
use App\Seeder\DatabaseSeeder;
use App\Seeder\PostSeeder;
use App\Seeder\TextGenerator;
use App\Seeder\ThemeCatalog;
use App\Service\SimilarPostsFinder;
use App\Support\Slugger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Random\Randomizer;

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

$container->set(CategoryRepositoryInterface::class, fn (ContainerInterface $c) => new PdoCategoryRepository(
    $c->get(PDO::class),
));
$container->set(PostRepositoryInterface::class, fn (ContainerInterface $c) => new PdoPostRepository(
    $c->get(PDO::class),
));

$container->set(SimilarPostsFinder::class, fn (ContainerInterface $c) => new SimilarPostsFinder(
    $c->get(PostRepositoryInterface::class),
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
    $c->get(CategoryRepositoryInterface::class),
    $c->get(PostRepositoryInterface::class),
    $c->get(TemplateRendererInterface::class),
));
$container->set(CategoryController::class, fn (ContainerInterface $c) => new CategoryController(
    $c->get(CategoryRepositoryInterface::class),
    $c->get(PostRepositoryInterface::class),
    $c->get(TemplateRendererInterface::class),
));
$container->set(PostController::class, fn (ContainerInterface $c) => new PostController(
    $c->get(PostRepositoryInterface::class),
    $c->get(CategoryRepositoryInterface::class),
    $c->get(SimilarPostsFinder::class),
    $c->get(TemplateRendererInterface::class),
));

$container->set(Randomizer::class, fn () => new Randomizer());
$container->set(Slugger::class, fn () => new Slugger());
$container->set(ThemeCatalog::class, fn () => new ThemeCatalog(require $settings['themes_file']));
$container->set(TextGenerator::class, fn (ContainerInterface $c) => new TextGenerator(
    $c->get(Randomizer::class),
));
$container->set(CategorySeeder::class, fn (ContainerInterface $c) => new CategorySeeder(
    $c->get(PDO::class),
    $c->get(ThemeCatalog::class),
    $c->get(Slugger::class),
));
$container->set(PostSeeder::class, fn (ContainerInterface $c) => new PostSeeder(
    $c->get(PDO::class),
    $c->get(ThemeCatalog::class),
    $c->get(TextGenerator::class),
    $c->get(Slugger::class),
    $c->get(Randomizer::class),
    array_map(
        fn (string $file): string => $settings['covers_url'] . '/' . basename($file),
        glob($settings['covers_dir'] . '/*.jpg') ?: [],
    ),
));
$container->set(DatabaseSeeder::class, fn (ContainerInterface $c) => new DatabaseSeeder(
    $c->get(PDO::class),
    $c->get(ThemeCatalog::class),
    [$c->get(CategorySeeder::class), $c->get(PostSeeder::class)],
));

return $container;
