<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Http\ControllerInterface;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\View\TemplateRendererInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\PostRepositoryInterface;

final readonly class HomeController implements ControllerInterface
{
    private const POSTS_PER_CATEGORY = 3;

    public function __construct(
        private CategoryRepositoryInterface $categories,
        private PostRepositoryInterface $posts,
        private TemplateRendererInterface $renderer,
    ) {
    }

    public function handle(Request $request): Response
    {
        return new Response($this->renderer->render('home.tpl', [
            'categories' => $this->categories->findNonEmpty(),
            'postsByCategory' => $this->posts->findLatestGroupedByCategory(self::POSTS_PER_CATEGORY),
        ]));
    }
}
