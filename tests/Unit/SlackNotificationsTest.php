<?php


namespace Tests\Unit;


use App\Notifications\SlackNotifications;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SlackNotificationsTest extends TestCase
{
    public function testSendNotification()
    {
        $slackUrl = env('SLACK_WEBHOOK_URL');
        $payload = 'This is a test of slack Notification';
        Notification::route('slack', $slackUrl)->notify(new SlackNotifications($payload));
    }
}
