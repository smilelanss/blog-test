{extends file="layout.tpl"}

{block name=content}
    <h1 class="page-title">Новые статьи</h1>

    {foreach $categories as $category}
        <section class="category-section">
            <header class="category-section__header">
                <h2 class="category-section__title">
                    <a href="/category/{$category->slug}">{$category->name}</a>
                </h2>
                <a class="category-section__more" href="/category/{$category->slug}">Все статьи ({$category->postsCount}) →</a>
            </header>

            <div class="post-grid">
                {foreach $postsByCategory[$category->id] as $post}
                    {include file="partials/post_card.tpl" post=$post}
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <p class="empty">Статей пока нет.</p>
    {/foreach}
{/block}
