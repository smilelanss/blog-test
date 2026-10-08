{function name=page_url}{$baseUrl}{if !$sort->isDefault()}?sort={$sort->value}{if $page > 1}&amp;page={$page}{/if}{elseif $page > 1}?page={$page}{/if}{/function}

{if $pagination->totalPages() > 1}
    <nav class="pagination" aria-label="Страницы">
        {if $pagination->hasPrevious()}
            <a class="pagination__link" href="{page_url page=$pagination->page - 1}" rel="prev" aria-label="Предыдущая страница">← <span class="pagination__label">Назад</span></a>
        {/if}

        {foreach $pagination->pages() as $number}
            {if $number === null}
                <span class="pagination__gap">…</span>
            {elseif $number === $pagination->page}
                <span class="pagination__link pagination__link--active" aria-current="page">{$number}</span>
            {else}
                <a class="pagination__link" href="{page_url page=$number}">{$number}</a>
            {/if}
        {/foreach}

        {if $pagination->hasNext()}
            <a class="pagination__link" href="{page_url page=$pagination->page + 1}" rel="next" aria-label="Следующая страница"><span class="pagination__label">Вперёд</span> →</a>
        {/if}
    </nav>
{/if}
