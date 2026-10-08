<?php

declare(strict_types=1);

namespace App\Core\Http;

use LogicException;

final readonly class Request
{
    /**
     * @param array<string, mixed> $query
     * @param array<string, string> $routeParams
     */
    public function __construct(
        private string $method,
        private string $path,
        private array $query = [],
        private array $routeParams = [],
    ) {
    }

    public static function fromGlobals(): self
    {
        $path = explode('?', $_SERVER['REQUEST_URI'] ?? '/', 2)[0];

        return new self($_SERVER['REQUEST_METHOD'] ?? 'GET', $path, $_GET);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function query(string $name, string $default = ''): string
    {
        $value = $this->query[$name] ?? null;

        return is_string($value) ? $value : $default;
    }

    public function queryInt(string $name, int $default = 0): int
    {
        $value = filter_var($this->query($name), FILTER_VALIDATE_INT);

        return $value === false ? $default : $value;
    }

    public function routeParam(string $name): string
    {
        if (!isset($this->routeParams[$name])) {
            throw new LogicException(sprintf('Параметр маршрута "%s" не задан.', $name));
        }

        return $this->routeParams[$name];
    }

    /**
     * @param array<string, string> $params
     */
    public function withRouteParams(array $params): self
    {
        return new self($this->method, $this->path, $this->query, $params);
    }
}
