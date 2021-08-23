<?php
namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\FormatterInterface;
use Ramsey\Uuid\Uuid;

class CronFormatter implements FormatterInterface
{
    private $sequenceUuid;

    private function generateSequenceUUID()
    {
        return UUID::uuid4()->toString();
    }

    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($this);
        }

        $this->sequenceUuid = $this->generateSequenceUUID();
    }

    private function generateContextParts($context)
    {
        $contextParts = [];

        foreach ($context as $keyOrIndex=>$value) {
            if (is_int($keyOrIndex)) {
                $contextParts[] = $value;
            } else {
                $contextParts[] = $keyOrIndex . ': ' . $value;
            }
        }

        return $contextParts;
    }

    public function format(array $record): string
    {
        $context = $record['context'];

        $cronParts = [
            $this->sequenceUuid,
            $record['channel'],
            $record['level_name']
        ];

        if (!empty($context)) {
            $cronParts = array_merge($cronParts, $this->generateContextParts($context));
        }

        $message = '';

        foreach ($cronParts as $cronPart) {
            $message .= "[$cronPart]";
        }

        $message .= " " . $record['message'] . PHP_EOL;

        return $message;
    }

    public function formatBatch(array $records)
    {
        $message = '';

        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }
}
