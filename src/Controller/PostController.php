<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Http\ControllerInterface;
use App\Core\Http\Exception\HttpNotFoundException;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\View\TemplateRendererInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Service\SimilarPostsFinder;

final readonly class PostController implements ControllerInterface
{
    private const SIMILAR_POSTS = 3;

    public function __construct(
        private PostRepositoryInterface $posts,
        private CategoryRepositoryInterface $categories,
        private SimilarPostsFinder $similarPosts,
        private TemplateRendererInterface $renderer,
    ) {
    }

    public function handle(Request $request): Response
    {
        $slug = $request->routeParam('slug');

        $this->posts->incrementViews($slug);
        $post = $this->posts->findBySlug($slug)
            ?? throw new HttpNotFoundException(sprintf('Статья "%s" не найдена.', $slug));

        return new Response($this->renderer->render('post.tpl', [
            'post' => $post,
            'categories' => $this->categories->findByPostId($post->id),
            'similarPosts' => $this->similarPosts->find($post, self::SIMILAR_POSTS),
        ]));
    }
}
