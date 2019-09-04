<?php
namespace App\Exception;

use Psr\Log\LogLevel;
use Throwable;

class MediaProcessing extends \Azura\Exception
{
    public function __construct(
        string $message = 'The media provided could not be processed.',
        int $code = 0,
        Throwable $previous = null,
        string $loggerLevel = LogLevel::ERROR
    ) {
        parent::__construct($message, $code, $previous, $loggerLevel);
    }
}
