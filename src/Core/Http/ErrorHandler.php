<?php

declare(strict_types=1);

namespace App\Core\Http;

use App\Core\Http\Exception\HttpNotFoundException;
use App\Core\View\TemplateRendererInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final readonly class ErrorHandler
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private LoggerInterface $logger,
        private bool $debug,
    ) {
    }

    public function handle(Throwable $exception): Response
    {
        try {
            if ($exception instanceof HttpNotFoundException) {
                return new Response($this->renderer->render('errors/404.tpl'), 404);
            }

            $this->logger->error($exception->getMessage(), ['exception' => $exception]);

            return new Response($this->renderer->render('errors/500.tpl', [
                'error' => $this->debug ? (string) $exception : null,
            ]), 500);
        } catch (Throwable $renderError) {
            $this->logger->critical('Не удалось показать страницу ошибки: {message}', [
                'message' => $renderError->getMessage(),
                'exception' => $renderError,
            ]);

            return new Response('Внутренняя ошибка сервера', 500, ['Content-Type' => 'text/plain; charset=utf-8']);
        }
    }
}
