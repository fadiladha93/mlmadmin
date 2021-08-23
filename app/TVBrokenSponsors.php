<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TVBrokenSponsors extends Model {

    protected $table = "tv_broken_sponsors";
    public $timestamps = false;

    public static function addNew($userId, $rec) {
        $agentId = $rec[0];
        $agentName = $rec[1];
        $webAlias = $rec[2];
        $email = $rec[3];
        $sponsor = $rec[4];
        $appdate = $rec[5];

        $r = new TVBrokenSponsors();
        $r->agent_id = $agentId;
        $r->agent_name = $agentName;
        $r->web_alias = $webAlias;
        $r->email = $email;
        $r->sponsor = $sponsor;
        $r->app_dt = \utill::getFormatedDate($appdate);
        $r->user_id = $userId;
        $r->save();
    }

}
