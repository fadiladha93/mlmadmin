<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserActivityHistory
 * @package App
 */
class UserActivityHistory extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'user_activity_history';

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'is_active',
        'is_activate',
        'is_bc_active',
        'created_at'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
