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
    }

    public function render(string $template, array $data = []): string
    {
        $tpl = $this->smarty->createTemplate($template);
        $tpl->assign($data);

        return $tpl->fetch();
    }
}
