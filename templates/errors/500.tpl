{extends file="layout.tpl"}

{block name=title prepend}Ошибка сервера — {/block}

{block name=content}
    <h1>Что-то пошло не так</h1>
    <p>На сервере произошла ошибка. Попробуйте обновить страницу позже. <a href="/">Перейти на главную</a></p>

    {if $error}
        <pre>{$error}</pre>
    {/if}
{/block}
