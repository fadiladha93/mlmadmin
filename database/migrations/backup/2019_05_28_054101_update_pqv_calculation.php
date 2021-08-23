<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePqvCalculation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE OR REPLACE FUNCTION public.calculate_downline_qv_with_tsa()
             RETURNS void
             LANGUAGE plpgsql
            AS $function$DECLARE 
             startdate_of_month timestamp;
             enddate_of_month timestamp;
             transac   RECORD;
             sponsor RECORD;
             cuser RECORD;
             rdef RECORD;
             sp_id varchar(100);
             counter integer :=0;
             rank_qv integer :=0;
             total_tsa integer :=0;
             rank_desc varchar(100);
             crank rank;
             crank2 tsarank;
             --tsarank type to get the tsa count and rankval
             trank tsarank;
             rank_exists integer :=0;
             curr_month_qv bigint:=0;
             curr_month_pqv bigint:=0;
             qualify_qv bigint:=0;
             
                --This variable is to sent inside rank history table Remarks field flaging how a user was promoted 
               --either by QV or TSA Count or False Promoted
             rank_remarks varchar(100);
             
             cur_transacs CURSOR (d1 date,d2 date)
                FOR SELECT * FROM vorder_product_qv where created_dt between d1 and d2;
            
             cur_all_users CURSOR
                FOR SELECT id,distid,COALESCE(current_month_qv, 0) as current_month_qv,COALESCE(current_month_tsa, 0) as current_month_tsa FROM users ;
                
             cur_rank_defs CURSOR
                FOR SELECT * FROM rank_definition ORDER BY rankval;
                 
             BEGIN
            
               
               TRUNCATE table "qv_transaction";
               --20190424 modified to update pqv=0 as every month during every time it is reset to 0
               update users set current_month_pqv=0,current_month_tsa=0,current_month_qv=0,current_month_rank=10;
               
               -- first date of month
               SELECT date_trunc(\'month\', NOW())::DATE into startdate_of_month;
               
               -- last date of month
               SELECT (date_trunc(\'month\', NOW())::DATE + interval \'1 month - 1 day\')::DATE into enddate_of_month;
               
               -- Open the cursor cur_transacs
               
               --20190424 modified to update pqv=0 as every month during every time it is reset to 0
               update users set current_month_pqv=0,current_month_tsa=0,current_month_qv=0,current_month_rank=10;
               
               --update all users PQV for the running month through and 20190528 fix pqv range from 1 month to -30 days
               update users set current_month_pqv=su.qv
               from (SELECT userid,sum(qv) as qv FROM vorder_product_qv 
                     where created_dt between date_trunc(\'day\', NOW())::DATE - interval \'30 days\' and date_trunc(\'day\', NOW())::DATE + interval \' + 1 day - 1 second\' group by userid) su
               where users.id=su.userid;
               
               --TODO
               --accumulate all users\' customers PQV of respective users along with the users PQV
               
               
             OPEN cur_transacs(startdate_of_month,enddate_of_month);    
               LOOP
                -- fetch row into the transac
                FETCH cur_transacs INTO transac;		
            
                -- exit when no more row to fetch
                EXIT WHEN NOT FOUND;	
            
                --a counter representing the upper level the sponsor
                counter:=1;				
            
                --go up the sponsor tree and add an QV entry to each user on the upline
            
                --pick the sponsor id of the user
                SELECT sponsorid into sp_id from public."users" where id=transac.userid;
                
                INSERT INTO qv_transaction (transaction_id,transaction_date, qv, user_id, level,initiated_user_id)
                    VALUES (transac.orderid,transac.created_dt, transac.qv, transac.userid, counter,transac.userid);							
                    
                    
                LOOP 
                    -- get the sponsor of the buyer, sponsor\'s sponsor and so on	
                    SELECT * FROM users INTO sponsor WHERE distid=sp_id;
                    -- exit the loop if there no longer a sponsor of the buyer or the sponsor
                    EXIT WHEN NOT FOUND; --or sponsor.id is null;	
            
                    INSERT INTO qv_transaction (transaction_id,transaction_date, qv, user_id, level,initiated_user_id)
                    VALUES (transac.orderid,transac.created_dt, transac.qv, sponsor.id, counter,transac.userid);							
                    sp_id:=sponsor.sponsorid;
                    --add one to the level to which we are going to add  QV next
                    counter := counter + 1 ; 
                        
                END LOOP ; 					
                         
                
               END LOOP;
               -- Close the cursor cur_transacs
               CLOSE cur_transacs; 
               
               
              
               -- Update the current month qv of each user in the user table  
               UPDATE users SET current_month_qv=sq.totalqv
               FROM (select user_id,sum(qv) as totalqv from qv_transaction group by user_id) as sq
               WHERE sq.user_id=users.id;
               
                  -- Open the cursor all_users
               OPEN cur_all_users;       
               LOOP
                    -- fetch row into the cur_user
                    FETCH cur_all_users INTO cuser;
                    -- exit when no more row to fetch
                    EXIT WHEN NOT FOUND;	
                    
                    --getcount of active TSA
                    SELECT count(1) into total_tsa FROM enrolment_tree_tsa(cuser.distid) where current_month_pqv>=100 and distid!=cuser.distid;
                        
                    --Update current month TSA count of each user
                    UPDATE users SET current_month_tsa=total_tsa where id=cuser.id;  
                
               END LOOP;
               -- Close the cursor all_users
               CLOSE cur_all_users;  
               
               -- Open the cursor all_users
               OPEN cur_all_users;       
               LOOP
                    -- fetch row into the cur_user
                    FETCH cur_all_users INTO cuser;
                    -- exit when no more row to fetch
                    EXIT WHEN NOT FOUND;	
            
                    OPEN cur_rank_defs;
                    LOOP
                        FETCH cur_rank_defs INTO rdef;			
                        --exit when no more row to fetch
                        EXIT WHEN NOT FOUND;	   					
                
                        SELECT * FROM get_rank_by_percentage(cuser.distid,rdef.id,rdef.rank_limit,rdef.min_qv) INTO crank;
                        
                        SELECT * FROM get_active_tsa_count(cuser.distid,rdef.id,rdef.rank_limit,rdef.min_tsa) INTO crank2;
                        
                        rank_remarks:=\'QV\';
                        
                        --Means, QV rank not satisfied but TSA count rank is found
                        IF crank.rankval IS NULL OR crank2.rankval>crank.rankval THEN
                        --Assigning the TSA Count rank and rank definitions are assigned to the variable
                            crank.rankval = crank2.rankval;
                            crank.rankdesc = crank2.rankdesc;
                            rank_remarks:=\'TSA Count\';
                        END IF;			
                        
                        --SELECT count(1) into total_tsa FROM enrolment_tree_tsa(cuser.distid) where current_month_pqv>=100 and distid!=cuser.distid;
                        
                        --RAISE NOTICE \'Rank Value calculated %\',crank.rankval;			
                        IF crank.rankval IS NOT NULL THEN
                        
                            SELECT count(*) FROM rank_history INTO rank_exists WHERE users_id=cuser.id AND lifetime_rank=crank.rankval;
                            IF rank_exists<1 THEN
                                INSERT INTO rank_history(users_id,lifetime_rank,created_dt,remarks)  values(cuser.id,crank.rankval,now(),rank_remarks);
                            END IF;
            
                            --take the total qv from users
                            select current_month_qv into curr_month_qv from users where id=cuser.id;
                            
                            --Checking whether data already inserted to the user_rank_history table. 
                            select count(*) from user_rank_history into rank_exists where period=enddate_of_month and user_id=cuser.id;		
                                
                            if rank_exists>0 then 
                            --Data already available for a particular user for particular month, so update				
                                UPDATE public.user_rank_history 
                                SET monthly_rank=crank.rankval,monthly_rank_desc=crank.rankdesc,monthly_qv= curr_month_qv,qualified_qv=crank.rank_qv,qualified_tsa=round(coalesce(crank2.rank_tsa,0),0),monthly_tsa=cuser.current_month_tsa
                                WHERE user_id=cuser.id and period=enddate_of_month;		
                            else
                            --Data unavailable for a particular user for particular month, so insert
                                INSERT INTO public.user_rank_history(user_id, monthly_rank,monthly_rank_desc, period, monthly_qv,qualified_qv,qualified_tsa,monthly_tsa) 
                                VALUES (cuser.id, crank.rankval,crank.rankdesc,enddate_of_month, curr_month_qv,crank.rank_qv,round(coalesce(crank2.rank_tsa,0),0),cuser.current_month_tsa);
                            end if;	
            
                            --Updates the user table current rank 
                            update users set current_month_rank=crank.rankval where id=cuser.id;
                        END IF;
                        
                    END LOOP;
                    CLOSE cur_rank_defs;		
                            
                    
                    SELECT count(*) FROM rank_history INTO rank_exists WHERE users_id=cuser.id;
                    IF rank_exists<1 THEN
                        INSERT INTO rank_history(users_id,lifetime_rank,created_dt)  values(cuser.id,10,now());
                    END IF;
                    
               END LOOP;
               -- Close the cursor all_users
               CLOSE cur_all_users;      
               raise info \'Ended %\',now();
               
               --insert a record that this process ran on certain date and time
               truncate rank_log;
               insert into rank_log (worked_on) values(now());
                    
            END;$function$
            ;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('
            CREATE OR REPLACE FUNCTION public.calculate_downline_qv_with_tsa()
             RETURNS void
             LANGUAGE plpgsql
            AS $function$DECLARE 
             startdate_of_month timestamp;
             enddate_of_month timestamp;
             transac   RECORD;
             sponsor RECORD;
             cuser RECORD;
             rdef RECORD;
             sp_id varchar(100);
             counter integer :=0;
             rank_qv integer :=0;
             total_tsa integer :=0;
             rank_desc varchar(100);
             crank rank;
             crank2 tsarank;
             --tsarank type to get the tsa count and rankval
             trank tsarank;
             rank_exists integer :=0;
             curr_month_qv bigint:=0;
             curr_month_pqv bigint:=0;
             qualify_qv bigint:=0;
             
                --This variable is to sent inside rank history table Remarks field flaging how a user was promoted 
               --either by QV or TSA Count or False Promoted
             rank_remarks varchar(100);
             
             cur_transacs CURSOR (d1 date,d2 date)
                FOR SELECT * FROM vorder_product_qv where created_dt between d1 and d2;
            
             cur_all_users CURSOR
                FOR SELECT id,distid,COALESCE(current_month_qv, 0) as current_month_qv,COALESCE(current_month_tsa, 0) as current_month_tsa FROM users ;
                
             cur_rank_defs CURSOR
                FOR SELECT * FROM rank_definition ORDER BY rankval;
                 
             BEGIN
            
               
               TRUNCATE table "qv_transaction";
               --20190424 modified to update pqv=0 as every month during every time it is reset to 0
               update users set current_month_pqv=0,current_month_tsa=0,current_month_qv=0,current_month_rank=10;
               
               -- first date of month
               SELECT date_trunc(\'month\', NOW())::DATE into startdate_of_month;
               
               -- last date of month
               SELECT (date_trunc(\'month\', NOW())::DATE + interval \'1 month - 1 day\')::DATE into enddate_of_month;
               
               -- Open the cursor cur_transacs
               
               --20190424 modified to update pqv=0 as every month during every time it is reset to 0
               update users set current_month_pqv=0,current_month_tsa=0,current_month_qv=0,current_month_rank=10;
               
               --update all users PQV for the running month through 
               update users set current_month_pqv=su.qv
               from (SELECT userid,sum(qv) as qv FROM vorder_product_qv 
                     where created_dt between date_trunc(\'day\', NOW())::DATE + interval \' - 1 month\' and date_trunc(\'day\', NOW())::DATE group by userid) su
               where users.id=su.userid;
               
               --TODO
               --accumulate all users\' customers PQV of respective users along with the users PQV
               
               
             OPEN cur_transacs(startdate_of_month,enddate_of_month);    
               LOOP
                -- fetch row into the transac
                FETCH cur_transacs INTO transac;		
            
                -- exit when no more row to fetch
                EXIT WHEN NOT FOUND;	
            
                --a counter representing the upper level the sponsor
                counter:=1;				
            
                --go up the sponsor tree and add an QV entry to each user on the upline
            
                --pick the sponsor id of the user
                SELECT sponsorid into sp_id from public."users" where id=transac.userid;
                
                INSERT INTO qv_transaction (transaction_id,transaction_date, qv, user_id, level,initiated_user_id)
                    VALUES (transac.orderid,transac.created_dt, transac.qv, transac.userid, counter,transac.userid);							
                    
                    
                LOOP 
                    -- get the sponsor of the buyer, sponsor\'s sponsor and so on	
                    SELECT * FROM users INTO sponsor WHERE distid=sp_id;
                    -- exit the loop if there no longer a sponsor of the buyer or the sponsor
                    EXIT WHEN NOT FOUND; --or sponsor.id is null;	
            
                    INSERT INTO qv_transaction (transaction_id,transaction_date, qv, user_id, level,initiated_user_id)
                    VALUES (transac.orderid,transac.created_dt, transac.qv, sponsor.id, counter,transac.userid);							
                    sp_id:=sponsor.sponsorid;
                    --add one to the level to which we are going to add  QV next
                    counter := counter + 1 ; 
                        
                END LOOP ; 					
                         
                
               END LOOP;
               -- Close the cursor cur_transacs
               CLOSE cur_transacs; 
               
               
              
               -- Update the current month qv of each user in the user table  
               UPDATE users SET current_month_qv=sq.totalqv
               FROM (select user_id,sum(qv) as totalqv from qv_transaction group by user_id) as sq
               WHERE sq.user_id=users.id;
               
                  -- Open the cursor all_users
               OPEN cur_all_users;       
               LOOP
                    -- fetch row into the cur_user
                    FETCH cur_all_users INTO cuser;
                    -- exit when no more row to fetch
                    EXIT WHEN NOT FOUND;	
                    
                    --getcount of active TSA
                    SELECT count(1) into total_tsa FROM enrolment_tree_tsa(cuser.distid) where current_month_pqv>=100 and distid!=cuser.distid;
                        
                    --Update current month TSA count of each user
                    UPDATE users SET current_month_tsa=total_tsa where id=cuser.id;  
                
               END LOOP;
               -- Close the cursor all_users
               CLOSE cur_all_users;  
               
               -- Open the cursor all_users
               OPEN cur_all_users;       
               LOOP
                    -- fetch row into the cur_user
                    FETCH cur_all_users INTO cuser;
                    -- exit when no more row to fetch
                    EXIT WHEN NOT FOUND;	
            
                    OPEN cur_rank_defs;
                    LOOP
                        FETCH cur_rank_defs INTO rdef;			
                        --exit when no more row to fetch
                        EXIT WHEN NOT FOUND;	   					
                
                        SELECT * FROM get_rank_by_percentage(cuser.distid,rdef.id,rdef.rank_limit,rdef.min_qv) INTO crank;
                        
                        SELECT * FROM get_active_tsa_count(cuser.distid,rdef.id,rdef.rank_limit,rdef.min_tsa) INTO crank2;
                        
                        rank_remarks:=\'QV\';
                        
                        --Means, QV rank not satisfied but TSA count rank is found
                        IF crank.rankval IS NULL OR crank2.rankval>crank.rankval THEN
                        --Assigning the TSA Count rank and rank definitions are assigned to the variable
                            crank.rankval = crank2.rankval;
                            crank.rankdesc = crank2.rankdesc;
                            rank_remarks:=\'TSA Count\';
                        END IF;			
                        
                        --SELECT count(1) into total_tsa FROM enrolment_tree_tsa(cuser.distid) where current_month_pqv>=100 and distid!=cuser.distid;
                        
                        --RAISE NOTICE \'Rank Value calculated %\',crank.rankval;			
                        IF crank.rankval IS NOT NULL THEN
                        
                            SELECT count(*) FROM rank_history INTO rank_exists WHERE users_id=cuser.id AND lifetime_rank=crank.rankval;
                            IF rank_exists<1 THEN
                                INSERT INTO rank_history(users_id,lifetime_rank,created_dt,remarks)  values(cuser.id,crank.rankval,now(),rank_remarks);
                            END IF;
            
                            --take the total qv from users
                            select current_month_qv into curr_month_qv from users where id=cuser.id;
                            
                            --Checking whether data already inserted to the user_rank_history table. 
                            select count(*) from user_rank_history into rank_exists where period=enddate_of_month and user_id=cuser.id;		
                                
                            if rank_exists>0 then 
                            --Data already available for a particular user for particular month, so update				
                                UPDATE public.user_rank_history 
                                SET monthly_rank=crank.rankval,monthly_rank_desc=crank.rankdesc,monthly_qv= curr_month_qv,qualified_qv=crank.rank_qv,qualified_tsa=round(coalesce(crank2.rank_tsa,0),0),monthly_tsa=cuser.current_month_tsa
                                WHERE user_id=cuser.id and period=enddate_of_month;		
                            else
                            --Data unavailable for a particular user for particular month, so insert
                                INSERT INTO public.user_rank_history(user_id, monthly_rank,monthly_rank_desc, period, monthly_qv,qualified_qv,qualified_tsa,monthly_tsa) 
                                VALUES (cuser.id, crank.rankval,crank.rankdesc,enddate_of_month, curr_month_qv,crank.rank_qv,round(coalesce(crank2.rank_tsa,0),0),cuser.current_month_tsa);
                            end if;	
            
                            --Updates the user table current rank 
                            update users set current_month_rank=crank.rankval where id=cuser.id;
                        END IF;
                        
                    END LOOP;
                    CLOSE cur_rank_defs;		
                            
                    
                    SELECT count(*) FROM rank_history INTO rank_exists WHERE users_id=cuser.id;
                    IF rank_exists<1 THEN
                        INSERT INTO rank_history(users_id,lifetime_rank,created_dt)  values(cuser.id,10,now());
                    END IF;
                    
               END LOOP;
               -- Close the cursor all_users
               CLOSE cur_all_users;      
               raise info \'Ended %\',now();
               
               --insert a record that this process ran on certain date and time
               truncate rank_log;
               insert into rank_log (worked_on) values(now());
                    
            END;$function$
            ;
        ');
    }
}
