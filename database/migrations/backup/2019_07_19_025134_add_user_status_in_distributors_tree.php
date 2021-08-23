<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserStatusInDistributorsTree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP FUNCTION IF EXISTS get_distributors_tree(character);');

        DB::statement('
CREATE OR REPLACE FUNCTION public.get_distributors_tree(dist_id character)
    RETURNS TABLE(
    id bigint,
    firstname character varying,
    lastname character varying,
    username character varying,
    distid character varying,
    sponsorid character varying,
    current_product_id integer,
    usertype integer,
    account_status character varying,
    is_active smallint
    ) 
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
BEGIN
    RETURN QUERY 
    WITH RECURSIVE distributors AS (
		 SELECT u.id, u.firstname, u.lastname, u.username, u.distid, u.sponsorid, u.current_product_id, u.usertype, u.account_status, u.is_active
		 FROM users u
		 WHERE u.distid = dist_id
		 UNION
		 SELECT sp.id, sp.firstname, sp.lastname, sp.username, sp.distid, sp.sponsorid, sp.current_product_id, sp.usertype, sp.account_status, sp.is_active
		 FROM users sp
		 INNER JOIN distributors d ON d.distid = sp.sponsorid 
		)
		SELECT d.id, d.firstname, d.lastname, d.username, d.distid, d.sponsorid, d.current_product_id, d.usertype, d.account_status, d.is_active
		FROM distributors d;
 END
$BODY$;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP FUNCTION IF EXISTS get_distributors_tree(character);');

        DB::statement('
CREATE OR REPLACE FUNCTION public.get_distributors_tree(dist_id character)
    RETURNS TABLE(
    id bigint,
    firstname character varying,
    lastname character varying,
    username character varying,
    distid character varying,
    sponsorid character varying,
    current_product_id integer,
    usertype integer,
    account_status character varying
    ) 
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
BEGIN
    RETURN QUERY 
    WITH RECURSIVE distributors AS (
		 SELECT u.id, u.firstname, u.lastname, u.username, u.distid, u.sponsorid, u.current_product_id, u.usertype, u.account_status
		 FROM users u
		 WHERE u.distid = dist_id
		 UNION
		 SELECT sp.id, sp.firstname, sp.lastname, sp.username, sp.distid, sp.sponsorid, sp.current_product_id, sp.usertype, sp.account_status
		 FROM users sp
		 INNER JOIN distributors d ON d.distid = sp.sponsorid 
		)
		SELECT d.id, d.firstname, d.lastname, d.username, d.distid, d.sponsorid, d.current_product_id, d.usertype, d.account_status
		FROM distributors d;
 END
$BODY$;
        ');
    }
}
