<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('key', 128);
            $table->string('value', 128);
            $table
                ->foreign('user_id', 'user_id_ssfk_1')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade')
            ;
        });

        $this->seedAdminSettings();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('site_settings');
    }

    /**
     * TODO: move to seeds
     */
    private function seedAdminSettings()
    {
        // yes it should be processed via seeds
        DB::table('site_settings')->insert([
            'key' => 'is_holding_tank_active',
            'value' => '1',
        ]);

    }
}
