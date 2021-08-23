<?php

use App\BinaryCommission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToUnilevelAndLeadership extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unilevel_commission', function (Blueprint $table) {
            $table->string('status', 100)->default(\App\Services\UnilevelCommission::PAID_STATUS);
        });

        Schema::table('leadership_commission', function (Blueprint $table) {
            $table->string('status', 100)->default(\App\Services\UnilevelCommission::PAID_STATUS);
        });

        DB::statement(
            "UPDATE unilevel_commission SET status = :paidStatus WHERE is_processed = :paid",
            [
                'paidStatus' => \App\Services\UnilevelCommission::PAID_STATUS,
                'paid' => true
            ]
        );

        DB::statement(
            "UPDATE unilevel_commission SET status = :calculatedStatus WHERE is_processed = :calculated",
            [
                'calculatedStatus' => BinaryCommission::CALCULATED_STATUS,
                'calculated' => false
            ]
        );

        DB::statement(
            "UPDATE leadership_commission SET status = :paidStatus WHERE is_processed = :paid",
            [
                'paidStatus' => \App\Services\UnilevelCommission::PAID_STATUS,
                'paid' => true
            ]
        );

        DB::statement(
            "UPDATE leadership_commission SET status = :calculatedStatus WHERE is_processed = :calculated",
            [
                'calculatedStatus' => BinaryCommission::CALCULATED_STATUS,
                'calculated' => false
            ]
        );

        DB::statement("ALTER TABLE unilevel_commission DROP is_processed");
        DB::statement("ALTER TABLE leadership_commission DROP is_processed");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unilevel_commission', function (Blueprint $table) {
            $table->boolean('is_processed')->default(0);
        });
        Schema::table('leadership_commission', function (Blueprint $table) {
            $table->boolean('is_processed')->default(0);
        });

        DB::statement(
            "UPDATE unilevel_commission SET is_processed = :paid WHERE status = :paidStatus",
            [
                'paid' => true,
                'paidStatus' => \App\Services\UnilevelCommission::PAID_STATUS
            ]
        );

        DB::statement(
            "UPDATE leaadership_commission SET is_processed = :paid WHERE status = :paidStatus",
            [
                'paid' => true,
                'paidStatus' => \App\Services\LeadershipCommission::PAID_STATUS
            ]
        );

        DB::statement(
            "UPDATE unilevel_commission SET is_processed = :calculated WHERE status = :calculatedStatus OR status = :postedStatus",
            [
                'calculated' => false,
                'calculatedStatus' => \App\Services\UnilevelCommission::CALCULATED_STATUS,
                'postedStatus' => \App\Services\UnilevelCommission::POSTED_STATUS,
            ]
        );

        DB::statement(
            "UPDATE leadership_commission SET is_processed = :calculated WHERE status = :calculatedStatus OR status = :postedStatus",
            [
                'calculated' => false,
                'calculatedStatus' => \App\Services\LeadershipCommission::CALCULATED_STATUS,
                'postedStatus' => \App\Services\LeadershipCommission::POSTED_STATUS,
            ]
        );

        DB::statement("ALTER TABLE leadership_commission DROP status");
        DB::statement("ALTER TABLE unilevel_commission DROP status");
    }
}
