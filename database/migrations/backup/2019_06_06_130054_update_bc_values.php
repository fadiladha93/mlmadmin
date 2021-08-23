<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBcValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('UPDATE binary_commission SET amount_earned = 27260 WHERE user_id = 244 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 10610 WHERE user_id = 243 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 20000 WHERE user_id = 419 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 10860 WHERE user_id = 6956 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 3780 WHERE user_id = 414 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 3960 WHERE user_id = 384 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 4530 WHERE user_id = 247 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 4590 WHERE user_id = 8 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('UPDATE binary_commission SET amount_earned = 20000 WHERE user_id = 244 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 10000 WHERE user_id = 243 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 10000 WHERE user_id = 419 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 10000 WHERE user_id = 6956 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 3000 WHERE user_id = 414 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 3000 WHERE user_id = 384 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 3000 WHERE user_id = 247 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
        DB::statement('UPDATE binary_commission SET amount_earned = 3000 WHERE user_id = 8 AND week_ending = \'2019-06-02\' and is_processed = \'f\'');
    }
}
