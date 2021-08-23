<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RankHistory extends Model {

    /**
     * {@inheritDoc}
     */
    protected $table = 'rank_history';

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'users_id');
    }
}
