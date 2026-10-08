<?php

declare(strict_types=1);

namespace App\Seeder;

use Random\Randomizer;

final readonly class TextGenerator
{
    private const ACTION_TITLES = [
        'Как {subject}',
        'Что нужно знать, чтобы {subject}',
        '{n} ошибок, которые мешают {subject}',
        '{n} советов для тех, кто хочет {subject}',
        'Пошаговый план: как {subject}',
    ];

    private const TOPIC_TITLES = [
        'Коротко о главном: {subject}',
        '{subject}: главное за 10 минут',
        '{subject} — разбор на примерах',
        '{subject}: с чего начать',
        'Гид для новичков: {subject}',
    ];

    private const ACTION_DESCRIPTIONS = [
        'Разбираемся, как {subject}, и собираем советы, которые помогут не потерять время.',
        'Пошаговая инструкция для тех, кто хочет {subject} без лишних ошибок.',
        'Рассказываем, с чего начать, если вы решили {subject}, и что делать дальше.',
    ];

    private const TOPIC_DESCRIPTIONS = [
        '{subject} — коротко о главном: с чего начать, на что обратить внимание и каких ошибок избежать.',
        'Простое введение в тему «{subject}» с примерами из практики.',
        'Собрали главное о теме «{subject}»: основные понятия, полезные приёмы и частые ошибки.',
    ];

    private const ACTION_INTROS = [
        'Многие хотят {subject}, но откладывают, потому что не знают, с чего начать.',
        'Желание {subject} появляется у многих, и это вполне достижимая цель.',
    ];

    private const TOPIC_INTROS = [
        'Тема «{subject}» только на первый взгляд кажется сложной.',
        'О теме «{subject}» написано много, но главное умещается в несколько простых идей.',
    ];

    private const GENERIC_SENTENCES = [
        'Начните с малого и постепенно усложняйте задачу.',
        'Регулярность важнее интенсивности: лучше понемногу, но каждый день.',
        'Не бойтесь ошибаться — каждая ошибка показывает, что можно улучшить.',
        'Полезно записать цель и разбить её на небольшие шаги.',
        'Опыт других людей помогает избежать типичных ловушек.',
        'Первые результаты обычно становятся заметны уже через пару недель.',
        'Сравнивайте себя не с другими, а с собой месяц назад.',
        'Хорошая подготовка экономит больше сил, чем кажется.',
        'Если что-то не получается, попробуйте другой подход.',
        'Иногда стоит сделать паузу и посмотреть на задачу свежим взглядом.',
        'Не обязательно покупать всё сразу: начать можно с самого простого.',
        'Делитесь своими успехами — это хорошо мотивирует.',
        'Если сомневаетесь, спросите тех, кто уже прошёл этот путь.',
        'Интерес к делу помогает больше, чем сила воли.',
        'Простые решения часто оказываются самыми надёжными.',
        'Главное — не останавливаться после первых трудностей.',
    ];

    private const OUTROS = [
        'Главное — начать, а остальное придёт с практикой.',
        'Надеемся, эти советы помогут вам сделать первый шаг.',
        'Пробуйте, экспериментируйте и не бойтесь ошибок.',
    ];

    private const NUMBERS = ['5', '7', '10', '12'];

    public function __construct(
        private Randomizer $random,
    ) {
    }

    public function generate(Theme $theme): PostText
    {
        $isAction = $this->random->getInt(0, 1) === 0;

        [$subjects, $titles, $descriptions, $intros] = $isAction
            ? [$theme->actions, self::ACTION_TITLES, self::ACTION_DESCRIPTIONS, self::ACTION_INTROS]
            : [$theme->topics, self::TOPIC_TITLES, self::TOPIC_DESCRIPTIONS, self::TOPIC_INTROS];

        $replacements = [
            '{subject}' => $this->pick($subjects),
            '{n}' => $this->pick(self::NUMBERS),
        ];

        return new PostText(
            $this->capitalize(strtr($this->pick($titles), $replacements)),
            $this->capitalize(strtr($this->pick($descriptions), $replacements)),
            $this->content($theme, strtr($this->pick($intros), $replacements)),
        );
    }

    private function content(Theme $theme, string $intro): string
    {
        $sentences = $this->random->shuffleArray([...$theme->sentences, ...self::GENERIC_SENTENCES]);
        $paragraphs = [];
        $next = 0;

        for ($i = $this->random->getInt(4, 8); $i > 0; $i--) {
            $paragraph = [];

            for ($j = $this->random->getInt(3, 5); $j > 0; $j--) {
                $paragraph[] = $sentences[$next++ % count($sentences)];
            }

            $paragraphs[] = implode(' ', $paragraph);
        }

        $paragraphs[0] = $intro . ' ' . $paragraphs[0];
        $paragraphs[count($paragraphs) - 1] .= ' ' . $this->pick(self::OUTROS);

        return implode("\n\n", $paragraphs);
    }

    /**
     * @param list<string> $items
     */
    private function pick(array $items): string
    {
        return $items[$this->random->getInt(0, count($items) - 1)];
    }

    private function capitalize(string $text): string
    {
        return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
    }
}
