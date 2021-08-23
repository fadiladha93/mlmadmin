<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class CredentialsController extends Controller
{
    
    public function index(Setting $settings){
        $mail['driver'] = $settings->firstOrNew(['param'=>'MAIL_DRIVER']);
        $mail['host'] = $settings->firstOrNew(['param'=>'MAIL_HOST']);
        $mail['username'] = $settings->firstOrNew(['param'=>'MAIL_USERNAME']);
        $mail['password'] = $settings->firstOrNew(['param'=>'MAIL_PASSWORD']);
        $mail['from_name'] = $settings->firstOrNew(['param'=>'MAIL_FROM_NAME']);
        $mail['from_address'] = $settings->firstOrNew(['param'=>'MAIL_FROM_ADDRESS']);

        $sms['driver'] = $settings->firstOrNew(['param'=>'SMS_DRIVER']);
        $sms['token'] = $settings->firstOrNew(['param'=>'SMS_TOKEN']);
        $sms['from'] = $settings->firstOrNew(['param'=>'SMS_FROM']);
        $sms['sid'] = $settings->firstOrNew(['param'=>'SMS_SID']);
        
        $fa2['driver'] = $settings->firstOrNew(['param'=>'FA2_DRIVER']);
        $fa2['key'] = $settings->firstOrNew(['param'=>'FA2_KEY']);

        return view('admin.credentials.index')->with(
                    compact('mail', 'fa2', 'sms'));
    }

    public function store(Request $request, Setting $settings){
        $data = $request->all();
        $mail_driver = $settings->firstOrNew(['param'=>'MAIL_DRIVER']);
        $mail_driver->value = $data['mail']['driver'];
        $mail_driver->save();

        $mail_key = $settings->firstOrNew(['param'=>'MAIL_HOST']);
        $mail_key->value = $data['mail']['host'];
        $mail_key->save();

        $mail_key = $settings->firstOrNew(['param'=>'MAIL_USERNAME']);
        $mail_key->value = $data['mail']['username'];
        $mail_key->save();

        $mail_key = $settings->firstOrNew(['param'=>'MAIL_PASSWORD']);
        $mail_key->value = $data['mail']['password'];
        $mail_key->save();

        $mail_key = $settings->firstOrNew(['param'=>'MAIL_FROM_NAME']);
        $mail_key->value = $data['mail']['from_name'];
        $mail_key->save();

        $mail_key = $settings->firstOrNew(['param'=>'MAIL_FROM_ADDRESS']);
        $mail_key->value = $data['mail']['from_address'];
        $mail_key->save();

        $sms_driver = $settings->firstOrNew(['param'=>'SMS_DRIVER']);
        $sms_driver->value = $data['sms']['driver'];
        $sms_driver->save();

        $sms_token = $settings->firstOrNew(['param'=>'SMS_TOKEN']);
        $sms_token->value = $data['sms']['token'];
        $sms_token->save();

        $sms_sid = $settings->firstOrNew(['param'=>'SMS_SID']);
        $sms_sid->value = $data['sms']['sid'];
        $sms_sid->save();

        $sms_from = $settings->firstOrNew(['param'=>'SMS_FROM']);
        $sms_from->value = $data['sms']['from'];
        $sms_from->save();

        $fa2_driver = $settings->firstOrNew(['param'=>'FA2_DRIVER']);
        $fa2_driver->value = $data['fa2']['driver'];
        $fa2_driver->save();

        $fa2_key = $settings->firstOrNew(['param'=>'FA2_KEY']);
        $fa2_key->value = $data['fa2']['key'];
        $fa2_key->save();

        return redirect('/settings/credentials');

    }
}
