<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Http\ControllerInterface;
use App\Core\Http\Exception\HttpNotFoundException;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\View\TemplateRendererInterface;
use App\Enum\PostSort;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Support\Pagination;

final readonly class CategoryController implements ControllerInterface
{
    private const POSTS_PER_PAGE = 6;

    public function __construct(
        private CategoryRepositoryInterface $categories,
        private PostRepositoryInterface $posts,
        private TemplateRendererInterface $renderer,
    ) {
    }

    public function handle(Request $request): Response
    {
        $slug = $request->routeParam('slug');
        $category = $this->categories->findBySlug($slug)
            ?? throw new HttpNotFoundException(sprintf('Категория "%s" не найдена.', $slug));

        $sort = PostSort::tryFrom($request->query('sort')) ?? PostSort::default();
        $pagination = new Pagination(
            $this->posts->countByCategory($category->id),
            self::POSTS_PER_PAGE,
            max(1, $request->queryInt('page', 1)),
        );

        if ($pagination->page > $pagination->totalPages()) {
            throw new HttpNotFoundException(sprintf(
                'В категории "%s" нет страницы %d.',
                $slug,
                $pagination->page,
            ));
        }

        return new Response($this->renderer->render('category.tpl', [
            'category' => $category,
            'posts' => $this->posts->findByCategory(
                $category->id,
                $sort,
                $pagination->perPage,
                $pagination->offset(),
            ),
            'sort' => $sort,
            'sorts' => PostSort::cases(),
            'pagination' => $pagination,
        ]));
    }
}
