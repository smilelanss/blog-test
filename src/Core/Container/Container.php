<?php

declare(strict_types=1);

namespace App\Core\Container;

use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    /** @var array<string, callable(ContainerInterface): mixed> */
    private array $factories = [];

    /** @var array<string, mixed> */
    private array $instances = [];

    public function set(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
        unset($this->instances[$id]);
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (!$this->has($id)) {
            throw new ServiceNotFoundException($id);
        }

        return $this->instances[$id] = ($this->factories[$id])($this);
    }

    public function has(string $id): bool
    {
        return isset($this->factories[$id]);
    }
}
