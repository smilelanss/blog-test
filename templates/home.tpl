{extends file="layout.tpl"}

{block name=content}
    <h1>Новые статьи</h1>

    {foreach $categories as $category}
        <section class="category-section">
            <h2 class="category-section__title">
                <a href="/category/{$category->slug}">{$category->name}</a>
            </h2>

            <div class="post-grid">
                {foreach $postsByCategory[$category->id] as $post}
                    {include file="partials/post_card.tpl" post=$post}
                {/foreach}
            </div>

            <a class="button" href="/category/{$category->slug}">Все статьи ({$category->postsCount})</a>
        </section>
    {foreachelse}
        <p>Статей пока нет.</p>
    {/foreach}
{/block}
