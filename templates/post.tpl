{extends file="layout.tpl"}

{block name=title prepend}{$post->title} — {/block}

{block name=description}{$post->description}{/block}

{block name=content}
    <article class="post">
        <h1 class="post__title">{$post->title}</h1>

        <p class="post__meta">
            <time datetime="{$post->publishedAt->format('Y-m-d')}">{$post->publishedAt->format('d.m.Y')}</time>
            · Просмотров: {$post->views}
        </p>

        <ul class="post__categories">
            {foreach $categories as $category}
                <li><a class="badge" href="/category/{$category->slug}">{$category->name}</a></li>
            {/foreach}
        </ul>

        <img class="post__image" src="{$post->image|default:'/images/covers/placeholder.jpg'}" alt="{$post->title}" width="1200" height="630">

        <div class="post__content">
            {$post->content|paragraphs nofilter}
        </div>
    </article>

    {if $similarPosts}
        <section class="similar-posts">
            <h2 class="similar-posts__title">Похожие статьи</h2>

            <div class="post-grid">
                {foreach $similarPosts as $similarPost}
                    {include file="partials/post_card.tpl" post=$similarPost}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
