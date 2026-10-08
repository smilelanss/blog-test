<nav class="sort" aria-label="Сортировка">
    {foreach $sorts as $option}
        {if $option === $sort}
            <span class="sort__item sort__item--active" aria-current="true">{$option->label()}</span>
        {else}
            <a class="sort__item" href="{$baseUrl}{if !$option->isDefault()}?sort={$option->value}{/if}">{$option->label()}</a>
        {/if}
    {/foreach}
</nav>
