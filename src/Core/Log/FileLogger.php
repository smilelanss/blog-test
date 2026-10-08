<?php

declare(strict_types=1);

namespace App\Core\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Stringable;
use Throwable;

final class FileLogger extends AbstractLogger
{
    private const LEVELS = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    ];

    public function __construct(
        private readonly string $file,
    ) {
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        if (!in_array($level, self::LEVELS, true)) {
            throw new InvalidArgumentException(sprintf(
                'Неизвестный уровень логирования: %s.',
                var_export($level, true),
            ));
        }

        $exception = $context['exception'] ?? null;
        unset($context['exception']);

        $message = (string) $message;
        $replacements = [];

        foreach ($context as $key => $value) {
            $placeholder = '{' . $key . '}';

            if (str_contains($message, $placeholder) && (is_scalar($value) || $value instanceof Stringable)) {
                $replacements[$placeholder] = (string) $value;
                unset($context[$key]);
            }
        }

        $line = sprintf('[%s] %s: %s', date('Y-m-d H:i:s'), strtoupper($level), strtr($message, $replacements));

        if ($context !== []) {
            $line .= ' ' . json_encode(
                $context,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR,
            );
        }

        if ($exception instanceof Throwable) {
            $line .= PHP_EOL . $exception;
        }

        file_put_contents($this->file, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
