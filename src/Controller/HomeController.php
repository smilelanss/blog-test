<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Http\ControllerInterface;
use App\Core\Http\Request;
use App\Core\Http\Response;

final class HomeController implements ControllerInterface
{
    public function handle(Request $request): Response
    {
        return new Response('Главная');
    }
}
