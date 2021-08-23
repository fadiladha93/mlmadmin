<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixVusersandaddressesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP view vusersandaddresses');
        DB::statement('create or replace view vusersandaddresses as
            SELECT u.id,
                   u.firstname,
                   u.lastname,
                   u.email,
                   u.phonenumber,
                   u.account_status,
                   u.email_verified,
                   u.created_dt,
                   u.entered_by,
                   u.username,
                   u.basic_info_updated,
                   u.distid,
                   u.sponsorid,
                   u.current_product_id,
                   a.stateprov,
                   a.countrycode,
                   u.usertype,
                   a.addrtype
            FROM (users u
                   LEFT JOIN 
                        (select * from addresses where addrtype = \'3\' AND "primary" = 1) a
                        ON a.userid = u.id
                   );'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP view vusersandaddresses');
        DB::statement('create or replace view vusersandaddresses as
            SELECT u.id,
                   u.firstname,
                   u.lastname,
                   u.email,
                   u.phonenumber,
                   u.account_status,
                   u.email_verified,
                   u.created_dt,
                   u.entered_by,
                   u.username,
                   u.basic_info_updated,
                   u.distid,
                   u.sponsorid,
                   u.current_product_id,
                   a.stateprov,
                   a.countrycode,
                   u.usertype,
                   a.addrtype
            FROM (users u
                   LEFT JOIN addresses a ON (((u.id = a.userid) AND (a."primary" = 1))));');
    }
}
