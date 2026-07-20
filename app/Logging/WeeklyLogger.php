<?php

namespace App\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WeeklyLogger
{
    /**
     * Create a Monolog instance that writes to a file named after
     * the Monday of the current ISO week (Mon–Sun), e.g. laravel-week-2026-04-13.log
     */
    public function __invoke(array $config): Logger
    {
        $monday = new \DateTime();
        $monday->setISODate((int) $monday->format('o'), (int) $monday->format('W'), 1);
        $weekStart = $monday->format('Y-m-d');

        $path = storage_path("logs/laravel-week-{$weekStart}.log");

        $level  = $config['level'] ?? Logger::DEBUG;
        $logger = new Logger('weekly');
        $logger->pushHandler(new StreamHandler($path, Logger::toMonologLevel($level)));

        return $logger;
    }
}
