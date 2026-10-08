<?php

declare(strict_types=1);

namespace App\Core\View;

interface TemplateRendererInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = []): string;
}
