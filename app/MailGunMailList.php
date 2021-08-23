<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;
use App\Customer;


class MailGunMailList extends Model {

    protected $table = "mailgun_mail_list";
    public $timestamps = false;

    //
    const TYPE_STANDBY = "STANDBY";
    const TYPE_COACH = "COACH";
    const TYPE_BUSINESS = "BUSINESS";
    const TYPE_FIRST_CLASS = "FIRST_CLASS";
    const TYPE_EB_FIRST_CLASS = "EB_FIRST_CLASS";
    const TYPE_GRANDFATHERING = "GRANDFATHERING";
    const TYPE_PREMIUM_FC = "PREMIUM_FC";
    const TYPE_CUSTOMERS = "CUSTOMERS";
    const TYPE_TEST = "TEST";

    public static function addNewList($address, $name, $description) {
        \Mailgun::api()->post("lists", [
            'address' => $address,
            'name' => $name,
            'description' => $description,
            'access_level' => 'readonly'
        ]);
    }

    public static function addBulkUsers() {
        $recs = self::get();
        foreach ($recs as $rec) {
            $membersJson = array();
            if ($rec->product_id > 0) {
                $users = User::select('email', 'firstname', 'lastname', 'id')
                        ->where('usertype', UserType::TYPE_DISTRIBUTOR)
                        ->where('account_status', User::ACC_STATUS_APPROVED)
                        ->where('current_product_id', $rec->product_id)
                        ->where(function($sq) {
                            $sq->whereNull('sync_with_mailgun')
                            ->orWhere('sync_with_mailgun', 0);
                        })
                        ->limit(500)
                        ->get();
                foreach ($users as $user) {
                    $info = array("address" => $user->email, 'name' => $user->firstname . " " . $user->lastname);
                    array_push($membersJson, $info);
                    //
                    User::where('id', $user->id)
                            ->update([
                                'sync_with_mailgun' => 1
                    ]);
                }
            } else if ($rec->product_id == 0) {
                // customers
                $customers = Customer::select('name', 'email', 'id')
                        ->where(function($sq) {
                            $sq->whereNull('sync_with_mailgun')
                            ->orWhere('sync_with_mailgun', 0);
                        })
                        ->limit(500)
                        ->get();
                foreach ($customers as $customer) {
                    $info = array("address" => $customer->email, 'name' => $customer->name);
                    array_push($membersJson, $info);
                    //
                    Customer::where('id', $customer->id)
                            ->update([
                                'sync_with_mailgun' => 1
                    ]);
                }
            }
            $membersJson = json_encode($membersJson);
            \App\MailGunMailList::addBulk($rec->address, $membersJson);
        }
        dd('done');
    }

    public static function updateBulk() {
        $recs = self::get();
        foreach ($recs as $rec) {
            $users = User::select('email', 'firstname', 'lastname', 'id', 'account_status')
                    ->where('usertype', \App\UserType::TYPE_DISTRIBUTOR)
                    ->where('current_product_id', $rec->product_id)
                    ->where('sync_with_mailgun', -1)
                    ->limit(5)
                    ->get();
            foreach ($users as $user) {
                \App\MailGunMailList::deleteMail($rec->address, $user->email);
                //
                if ($user->account_status == User::ACC_STATUS_APPROVED) {
                    USer::where('id', $user->id)
                            ->update([
                                'sync_with_mailgun' => 0
                    ]);
                } else if ($user->account_status == User::ACC_STATUS_SUSPENDED ||
                        $user->account_status == User::ACC_STATUS_TERMINATED
                ) {
                    User::where('id', $user->id)
                            ->update([
                                'sync_with_mailgun' => 1
                    ]);
                }
            }
        }
    }

    public static function updateListCount() {
        $res = \Mailgun::api()->get("lists/pages");
        $items = $res->http_response_body->items;
        foreach ($items as $i) {
            self::where('address', $i->address)
                    ->update([
                        'no_of_members' => $i->members_count
            ]);
        }
    }

//    private static function addNewMail($type, $mail, $name) {
//        $address = self::getAddress($type);
//        $res = \Mailgun::api()->post("lists/{$address}/members", [
//            'address' => $mail,
//            'name' => $name,
//            'subscribed' => 'yes',
//            'upsert' => 'yes'
//        ]);
//        //dd($res);
//    }

    private static function addBulk($address, $membersJson) {
        $res = \Mailgun::api()->post("lists/{$address}/members.json", [
            'members' => $membersJson,
            'upsert' => 'yes'
        ]);
        //dd($res);
    }

    

    public static function deleteMail($address, $mail) {
        try {
            $res = \Mailgun::api()->delete("lists/{$address}/members/{$mail}");
        } catch (\Mailgun\Connection\Exceptions\MissingEndpoint $ex) {
            
        }
           
        //dd($res);
    }

    private static function getAddress($type) {
        $rec = self::select('address')
                ->where('type', $type)
                ->first();
        return $rec->address;
    }

}
