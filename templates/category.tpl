{extends file="layout.tpl"}

{block name=title prepend}{$category->name} — {/block}

{block name=description}{$category->description}{/block}

{block name=content}
    <header class="category-header">
        <h1>{$category->name}</h1>
        <p class="category-header__description">{$category->description}</p>
        <p class="category-header__count">Статей: {$pagination->total}</p>
    </header>

    {if $posts}
        {include file="partials/sort.tpl" baseUrl="/category/{$category->slug}"}

        <div class="post-grid">
            {foreach $posts as $post}
                {include file="partials/post_card.tpl" post=$post}
            {/foreach}
        </div>

        {include file="partials/pagination.tpl" baseUrl="/category/{$category->slug}"}
    {else}
        <p class="empty">Статей пока нет.</p>
    {/if}
{/block}
