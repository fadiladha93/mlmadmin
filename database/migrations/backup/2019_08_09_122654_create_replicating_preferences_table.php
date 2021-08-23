<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplicatingPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replicated_preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('business_name')->nullable();
            $table->string('displayed_name')->nullable();
            $table->integer('show_name')->default(1);
            $table->string('phone')->nullable();
            $table->boolean('show_phone')->default(0);
            $table->string('email')->nullable();
            $table->boolean('show_email')->default(0);
            $table->string('co_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replicated_preferences');
    }
}
