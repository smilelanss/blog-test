<?php

declare(strict_types=1);

namespace App\Core\View;

use Smarty\Smarty;

final readonly class SmartyRenderer implements TemplateRendererInterface
{
    private Smarty $smarty;

    public function __construct(string $templatesDir, string $compileDir)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($templatesDir);
        $this->smarty->setCompileDir($compileDir);
        $this->smarty->setEscapeHtml(true);
        $this->smarty->registerPlugin(Smarty::PLUGIN_MODIFIER, 'paragraphs', $this->paragraphs(...));
    }

    public function render(string $template, array $data = []): string
    {
        $tpl = $this->smarty->createTemplate($template);
        $tpl->assign($data);

        return $tpl->fetch();
    }

    private function paragraphs(string $text): string
    {
        $paragraphs = preg_split('/\R\s*\R/', trim($text), -1, PREG_SPLIT_NO_EMPTY);

        return implode(PHP_EOL, array_map(
            fn (string $paragraph): string => '<p>' . htmlspecialchars(trim($paragraph)) . '</p>',
            $paragraphs,
        ));
    }
}
