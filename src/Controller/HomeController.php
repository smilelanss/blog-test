<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Http\ControllerInterface;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\View\TemplateRendererInterface;

final readonly class HomeController implements ControllerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
    ) {
    }

    public function handle(Request $request): Response
    {
        return new Response($this->renderer->render('home.tpl'));
    }
}
