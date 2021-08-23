<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model for Site Settings
 *
 * @package App\Models
 */
class ForceRank extends Model
{
    /**
     * Name of the table.
     *
     * @var string
     */
    protected $table = 'force_rank';

    /**
     * Disable timestamps for this model.
     *
     * @var bool
     */
    public $timestamps = false;
}
