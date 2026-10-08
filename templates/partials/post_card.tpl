<article class="post-card">
    <a class="post-card__image" href="/post/{$post->slug}">
        <img src="{$post->image|default:'/images/covers/placeholder.jpg'}" alt="{$post->title}" width="1200" height="630" loading="lazy">
    </a>

    <h3 class="post-card__title">
        <a href="/post/{$post->slug}">{$post->title}</a>
    </h3>

    <p class="post-card__description">{$post->description}</p>

    <p class="post-card__meta">
        <time datetime="{$post->publishedAt->format('Y-m-d')}">{$post->publishedAt->format('d.m.Y')}</time>
        · Просмотров: {$post->views}
    </p>
</article>
