<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\User;
use MyMail;
use Tests\TestCase;
use Exception;
use \Bogardo\Mailgun\Facades\Mailgun;

class MailGunTest extends TestCase
{
    public function testMail()
    {
        $data = [
            'firstName' => 'Andrew',
            'enrolleeFirstName' => 'John',
            'enrolleeLastName' => 'Smith',
            'enrolleeEmail' => 'john@smith.com',
            'enrolleePhone' => '514-772-2343',
        ];

        Mail::send('emails.new-enrollee-notification-email', $data, function($message) {
            $message->to('andrew@simplyphp.com')->subject('You have a New Team Member');
        });
    }

    public function testRawMail()
    {
        Mail::raw('Sending emails with Mailgun and Laravel is easy!', function ($message) {
            $message->to('andrew@simplyphp.com');
        });
    }

    public function testSendmailMail()
    {
        $distId = 'TSA4460722';
        try {
            if (!$user = User::where('distid', $distId)->first()) {
                return response()->json(['error' => 1, 'msg' => 'User could not be found']);
            }

            $msg = 'Welcome Email resent succesfully!';

            if ($error = MyMail::resendWelcomeEmail($user)) {
                $msg = "Couldn't resend Welcome Email";
            }

            $jsonData = ['error' => $error, 'msg' => $msg];
        } catch(Exception $e) {
            $exceptionMessage = '[' . __CLASS__ . '][' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($exceptionMessage);

            $jsonData = ['error' => 1, 'msg' => 'Unknown error'];
        }

        $this->assertEquals(0, $jsonData['error']);
    }
}
