<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{block name=title}Блог{/block}</title>
    <meta name="description" content="{block name=description}Блог со статьями на разные темы{/block}">
</head>
<body>
    <header>
        <a href="/">Блог</a>
    </header>

    <main>
        {block name=content}{/block}
    </main>

    <footer>
        © {$smarty.now|date_format:'%Y'} Блог
    </footer>
</body>
</html>
