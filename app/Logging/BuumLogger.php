<?php


namespace App\Logging;


use App\Notifications\SlackNotifications;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Console\Output\ConsoleOutput;
use \Illuminate\Support\Facades\Log;

class BuumLogger
{
    const LOG_TYPE_INFO     = 'info';
    const LOG_TYPE_WARNING  = 'warning';
    const LOG_TYPE_CRITICAL = 'critical';

    private $message;
    private $logType;
    public function __construct($message, $logType)
    {
        $this->message = $message;
        $this->logType = $logType;
    }

    public function toConsole()
    {
        $out = new ConsoleOutput();
        $out->writeln($this->message);
    }

    public function toSlack()
    {
        Notification::route('slack', config('consts.slack_webhook_log_url'))
            ->notify(new SlackNotifications($this->message));
    }

    public function toLog()
    {
        switch ($this->logType) {
            case self::LOG_TYPE_CRITICAL:
                Log::critical($this->message);
                break;
            case self::LOG_TYPE_WARNING:
                Log::warning($this->message);
                break;
            default:
                Log::info($this->message);
        }
    }

    public function toAll()
    {
        $this->toConsole();
        $this->toLog();
        $this->toSlack();
    }

    public function toSlackAndLog()
    {
        $this->toSlack();
        $this->toLog();
    }
}
