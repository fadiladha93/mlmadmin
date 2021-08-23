<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class MailGunMailListController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin');
    }

    public function addNewList() {
        $recs = DB::table('mailgun_mail_list')
                ->get();
        foreach ($recs as $rec) {
            \App\MailGunMailList::addNewList($rec->address, $rec->name, $rec->name);
        }
        echo "done";
    }

//    public function addNewMail() {
//        $mail = "s.inshaf@gmail.com";
//        $name = "inshaf sabar";
//        \App\MailGunMailList::addNewMail(\App\MailGunMailList::TYPE_TEST, $mail, $name);
//        echo "done";
//    }

    public function addBulk() {
        \App\MailGunMailList::addBulkUsers();
    }

    public function updateBulk() {
        \App\MailGunMailList::updateBulk();
    }

//    private function deleteMail() {
//        $mail = "aronda98@hotmail.com";
//        \App\MailGunMailList::deleteMail(\App\MailGunMailList::TYPE_STANDBY, $mail);
//    }

}
