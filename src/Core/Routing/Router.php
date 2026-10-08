<?php

declare(strict_types=1);

namespace App\Core\Routing;

final readonly class Router
{
    /**
     * @param list<Route> $routes
     */
    public function __construct(
        private array $routes,
    ) {
    }

    public function match(string $method, string $path): ?RouteMatch
    {
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        foreach ($this->routes as $route) {
            if ($route->method !== $method) {
                continue;
            }

            if (preg_match($this->compile($route->path), $path, $matches) === 1) {
                return new RouteMatch($route, array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
            }
        }

        return null;
    }

    private function compile(string $path): string
    {
        return '#^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[a-z0-9-]+)', $path) . '$#';
    }
}
