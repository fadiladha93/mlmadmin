<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBinaryQualificationFix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('create or replace function binary_qualification() returns void
          language plpgsql
        as
        $$
        declare
        lft integer;
        rgt integer;
        sp_id integer;
        transac RECORD;
        user_transac RECORD;
        
        cur_transac CURSOR (r integer,l integer, sp_id integer)
        	FOR select distinct(a.direction) 
        from binary_plan a, users b
        where a.user_id = b.id
        and a._rgt < r and a._lft > l
        and b.sponsorid = (select users.distid from users where id = sp_id )
        and b.is_active = 1;
        
        cur_user_transac cursor 
        	for select id,distid from users;
        
        begin
        
        open cur_user_transac;
        loop
          -- fetch row into the transac
            FETCH cur_user_transac INTO user_transac;
        	-- exit when no more row to fetch
        	EXIT WHEN NOT FOUND;
        	
        	update users set binary_q_l=0,binary_q_r=0 where id=user_transac.id;
        	
        	select user_id, _lft,_rgt from binary_plan into sp_id,lft,rgt where user_id = user_transac.id;
        	open cur_transac (rgt,lft, sp_id);
        	LOOP
        		-- fetch row into the transac
        		FETCH cur_transac INTO transac;
        		-- exit when no more row to fetch
        		EXIT WHEN NOT FOUND;
        		
        		if transac.direction=\'L\' then
        			update users set binary_q_l=1 where id=user_transac.id;
        		else
        			update users set binary_q_r=1 where id=user_transac.id;
        		end if;
        	end loop;
        	CLOSE cur_transac;  
        end loop;
        close cur_user_transac;
        end;
        $$;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('create or replace function binary_qualification() returns void
          language plpgsql
        as
        $$
        declare
        lft integer;
        rgt integer;
        sp_id integer;
        transac RECORD;
        user_transac RECORD;
        
        cur_transac CURSOR (r integer,l integer, sp_id integer)
        	FOR select distinct(a.direction) 
        from binary_plan a, users b
        where a.user_id = b.id
        and a._rgt < r and a._lft > l
        and a.sponsor_id = sp_id
        and b.is_active = 1;
        
        cur_user_transac cursor 
        	for select id,distid from users;
        
        begin
        
        open cur_user_transac;
        loop
          -- fetch row into the transac
            FETCH cur_user_transac INTO user_transac;
        	-- exit when no more row to fetch
        	EXIT WHEN NOT FOUND;
        	
        	update users set binary_q_l=0,binary_q_r=0 where id=user_transac.id;
        	
        	select user_id, _lft,_rgt from binary_plan into sp_id,lft,rgt where user_id = user_transac.id;
        	open cur_transac (rgt,lft, sp_id);
        	LOOP
        		-- fetch row into the transac
        		FETCH cur_transac INTO transac;
        		-- exit when no more row to fetch
        		EXIT WHEN NOT FOUND;
        		
        		if transac.direction=\'L\' then
        			update users set binary_q_l=1 where id=user_transac.id;
        		else
        			update users set binary_q_r=1 where id=user_transac.id;
        		end if;
        	end loop;
        	CLOSE cur_transac;  
        end loop;
        close cur_user_transac;
        end;
        $$;');
    }
}
