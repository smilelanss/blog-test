<article class="post-card">
    {if $post->image}
        <a class="post-card__image" href="/post/{$post->slug}">
            <img src="{$post->image}" alt="{$post->title}" width="1200" height="630" loading="lazy">
        </a>
    {/if}

    <h3 class="post-card__title">
        <a href="/post/{$post->slug}">{$post->title}</a>
    </h3>

    <p class="post-card__description">{$post->description}</p>

    <p class="post-card__meta">
        <time datetime="{$post->publishedAt->format('Y-m-d')}">{$post->publishedAt->format('d.m.Y')}</time>
        · Просмотров: {$post->views}
    </p>
</article>
