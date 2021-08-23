<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CalculateBoomerang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        create or replace  function calculate_bomerangs() returns void
          language plpgsql
        as
        $$
        DECLARE
        
          start_date date;
          end_date date;
          sponsor record;
          coach_pack_boomerang integer := 10;
          business_pack_boomerang integer := 20;
          first_class_pack_boomerang integer := 30;
          r record;
        
          sponsor_users CURSOR(start_date date, end_date date)
            FOR select  users.sponsorid, count(sponsorid) as counts from users
                where created_date between start_date and end_date
            group by users.sponsorid having count(sponsorid) >=5 order by counts DESC;
        
        BEGIN
        
          SELECT (date_trunc('month', NOW())::DATE - interval '1 month')::DATE into start_date;
        
          -- last date of month (MAY)
          SELECT (date_trunc('month', NOW())::DATE - interval '1 day')::DATE into end_date;
        
        
          -- Open the cursor cur_transacs
          OPEN sponsor_users(start_date, end_date);
          LOOP
            -- fetch row into the sponsor
            FETCH sponsor_users INTO sponsor;
            -- exit when no more row to fetch
            EXIT WHEN NOT FOUND;
        
            FOR r IN
              select productid, count(productid) as count from users as u
              join orders as o on o.userid = u.id
              join \"orderItem\" as oi on oi.orderid = o.id
              join products as p on oi.productid = p.id
        
              where sponsorid = sponsor.sponsorid and p.id in (2,3,4)
                and u.created_date  between start_date and end_date
              group by p.id, productid
        
              LOOP
                -- здесь возможна обработка данных
                RAISE NOTICE 'log % % %', sponsor.sponsorid, r.productid, r.count ;
        
                IF r.productid = 2 THEN
                    update boomerang_inv set available_tot = available_tot + coach_pack_boomerang*r.count
                    from(select id from users where distid = sponsor.sponsorid) as u
                    where u.id = boomerang_inv.userid ;
                end if;
                IF r.productid = 3 THEN
                  update boomerang_inv set available_tot = available_tot + business_pack_boomerang*r.count
                  from(select id from users where distid = sponsor.sponsorid) as u
                  where u.id = boomerang_inv.userid ;
                end if;
                IF r.productid = 4 THEN
                  update boomerang_inv set available_tot = available_tot + first_class_pack_boomerang*r.count
                  from(select id from users where distid = sponsor.sponsorid) as u
                  where u.id = boomerang_inv.userid ;
                end if;
              END LOOP;
        
          END LOOP;
          -- Close the cursor sponsor_users
          CLOSE sponsor_users;
        
        END;
        $$;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('drop function if exists calculate_bomerangs()');
    }
}
