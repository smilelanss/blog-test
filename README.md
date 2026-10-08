# Блог на PHP, MySQL и Smarty

## Запуск

Нужны Docker и Docker Compose.

```bash
cp .env.example .env
docker compose up -d --build
```

Сайт: http://localhost:8080. Порт меняется в `.env` (`APP_PORT`).

## Миграции

Применяются автоматически при каждом старте контейнера `php`. Запустить вручную:

```bash
docker compose exec php php bin/migrate.php
```

## Сиды

```bash
docker compose exec php php bin/seed.php --fresh
```

- `--categories=N` — число категорий, от 2 до 10 (по умолчанию 8);
- `--posts=N` — число статей (по умолчанию 60);
- `--fresh` — очистить таблицы перед заполнением, без него сидер работает только на пустой базе.
