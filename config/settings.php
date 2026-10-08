<?php

declare(strict_types=1);

return [
    'debug' => filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOL),
    'templates_dir' => dirname(__DIR__) . '/templates',
    'smarty_compile_dir' => dirname(__DIR__) . '/var/smarty',
    'log_file' => dirname(__DIR__) . '/var/log/app.log',
];
