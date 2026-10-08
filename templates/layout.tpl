<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{block name=title}Блог{/block}</title>
    <meta name="description" content="{block name=description}Блог со статьями на разные темы{/block}">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <header class="site-header">
        <div class="container site-header__inner">
            <a class="site-header__logo" href="/">Блог</a>
        </div>
    </header>

    <main class="container site-main">
        {block name=content}{/block}
    </main>

    <footer class="site-footer">
        <div class="container">© {$smarty.now|date_format:'%Y'} Блог</div>
    </footer>
</body>
</html>
