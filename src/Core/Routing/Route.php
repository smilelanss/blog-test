<?php

declare(strict_types=1);

namespace App\Core\Routing;

use App\Core\Http\ControllerInterface;

final readonly class Route
{
    /**
     * @param class-string<ControllerInterface> $controller
     */
    public function __construct(
        public string $method,
        public string $path,
        public string $controller,
    ) {
    }
}
