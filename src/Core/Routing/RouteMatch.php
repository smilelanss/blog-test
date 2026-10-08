<?php

declare(strict_types=1);

namespace App\Core\Routing;

final readonly class RouteMatch
{
    /**
     * @param array<string, string> $params
     */
    public function __construct(
        public Route $route,
        public array $params,
    ) {
    }
}
