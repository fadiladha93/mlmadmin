<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $table = "exports";

    public static function fieldWatchExport()
    {
        $users = DB::table('users')
            ->join('exports', 'users.id', '=', 'exports.user_id', 'left outer')
            ->select('users.account_status', 'users.firstname', 'users.lastname', 'users.email', 'users.username', 'users.business_name', 'users.sponsorid', 'users.mobilenumber', 'users.created_date', 'users.id')
            ->where('exports.user_id', null)
            ->orderBy('users.id', 'asc')
            ->limit(1000)
            ->get();
        $users = json_decode(json_encode($users), true);
        $users = array_map(function ($tag) {
            return array(
                'id' => $tag['id'],
                'account_status' => $tag['account_status'],
                'first_name' => $tag['firstname'],
                'last_name' => $tag['lastname'],
                'email' => $tag['email'],
                'website' => 'http://' . $tag['username'] . '.ibuumerang.com',
                'username' => $tag['username'],
                'company_name' => (empty($tag['business_name']) ? '' : $tag['business_name']),
                'sponsor_id' => $tag['sponsorid'],
                'phone' => (empty($tag['mobilenumber']) ? '' : $tag['mobilenumber']),
                'join_date' => (!empty($tag['created_date']) ? date("m/d/Y", strtotime($tag['created_date'])) : null),
            );
        }, $users);

        if (empty($users)) {
            echo json_encode(["status" => "success", "msg" => "There is no users to export."]);
        }
        $userIds = array_column($users, 'id');
        $representatives = ['representatives' => $users, 'overwrite' => ""];
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Token token="fd30890480b3be5b8dc73e9a98a61f87"',
        );
        $client = new \GuzzleHttp\Client();
        $options = [
            'headers' => $headers,
            'json' => $representatives
        ];
        $response = $client->post('https://fieldwatch.co/api/v1/clients/172/representative_imports/json', $options);
        $response = json_decode((string)$response->getBody());
        if ($response->status == "ok") {
            foreach ($userIds as $userId) {
                \App\Export::insert(['user_id' => $userId, 'status' => 1, 'export_to' => 'fieldwatch', 'created_at' => date("Y-m-d h:i:s a", time())]);
            }
        }

    }
}
