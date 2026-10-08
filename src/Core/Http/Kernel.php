<?php

declare(strict_types=1);

namespace App\Core\Http;

use App\Core\Http\Exception\HttpNotFoundException;
use App\Core\Routing\Router;
use LogicException;
use Psr\Container\ContainerInterface;
use Throwable;

final readonly class Kernel
{
    public function __construct(
        private Router $router,
        private ContainerInterface $container,
        private ErrorHandler $errorHandler,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $match = $this->router->match($request->method(), $request->path());

            if ($match === null) {
                throw new HttpNotFoundException(sprintf(
                    'Маршрут %s %s не найден.',
                    $request->method(),
                    $request->path(),
                ));
            }

            $controller = $this->container->get($match->route->controller);

            if (!$controller instanceof ControllerInterface) {
                throw new LogicException(sprintf(
                    'Контроллер %s должен реализовывать %s.',
                    $match->route->controller,
                    ControllerInterface::class,
                ));
            }

            return $controller->handle($request->withRouteParams($match->params));
        } catch (Throwable $exception) {
            return $this->errorHandler->handle($exception);
        }
    }
}
