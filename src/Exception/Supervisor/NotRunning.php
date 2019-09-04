<?php
namespace App\Exception\Supervisor;

use Psr\Log\LogLevel;
use Throwable;

class NotRunning extends \App\Exception\Supervisor
{
    public function __construct(
        string $message = 'Process was not running yet.',
        int $code = 0,
        Throwable $previous = null,
        string $loggerLevel = LogLevel::INFO
    ) {
        parent::__construct($message, $code, $previous, $loggerLevel);
    }
}
