<?php

declare(strict_types=1);

namespace App\Core\Container;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

final class ServiceNotFoundException extends RuntimeException implements NotFoundExceptionInterface
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Сервис "%s" не зарегистрирован в контейнере.', $id));
    }
}
