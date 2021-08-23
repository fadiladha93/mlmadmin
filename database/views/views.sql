GRANT USAGE, SELECT ON SEQUENCE cities_id_seq TO public;



CREATE VIEW lead_intern AS
SELECT leads.name, leads.email, leads.phone, leads.contact_date, leads.status, CONCAT(users.first_name,' ',users.surname, ' (', users.email ,')') AS intern_detail
FROM leads, users
WHERE leads.added_by = users.id;

CREATE VIEW customer_intern AS
SELECT
customers.id,
CONCAT(COALESCE(customers.first_name, ''),' ',COALESCE(customers.last_name, '')) AS customer_name, customers.email,
CONCAT(COALESCE(customers.address1, ''),' | ',COALESCE(customers.address2, '')) AS address,
CONCAT(COALESCE(customers.city, ''),' | ',COALESCE(customers.state, ''),' | ', COALESCE(customers.zip, ''),' | ', COALESCE(customers.country, '')) AS location,
customers.phone,
CONCAT(users.first_name,' ',users.surname, ' (', users.email ,')') AS sponsor_detail
FROM customers
LEFT JOIN users
ON customers.referrer_id = users.id;

CREATE VIEW transaction_detail AS
SELECT a.id,
CONCAT(b.customer_name, ' (',b.email,')') AS customer,
b.sponsor_detail,
a.total,
CONCAT(COALESCE(a.product_sku, ''),' - ',COALESCE(a.product_name, ''),' - ' ,COALESCE(a.product_price, '')) AS product_info
FROM transactions a, customer_intern b
WHERE a.customer_id = b.id;

CREATE VIEW transaction_with_user AS
SELECT CONCAT(COALESCE(c.first_name, ''),' ',COALESCE(c.last_name, '')) AS customer_name, c.email, CONCAT(COALESCE(a.product_sku, ''),' - ',COALESCE(a.product_name, '')) AS product_info, a.product_price, a.total, b.id AS  user_id
FROM transactions a, users b, customers c
WHERE a.customer_id = c.id
AND c.referrer_id = b.id;

--  < created by mahran >
CREATE VIEW vorderuserspaymentmethods AS
SELECT o.statuscode,o.id AS order_id,o.ordersubtotal,o.ordertotal,o.orderbv,o.payment_methods_id,o.shipping_address_id,o.created_date,u.distid,pmt.pay_method_name
FROM orders o
JOIN users u ON o.userid = u.id
LEFT JOIN payment_methods pm ON o.payment_methods_id = pm.id
LEFT JOIN payment_method_type pmt ON pm.pay_method_type = pmt.id;


CREATE VIEW vpersonalenrolleddistributors AS
SELECT u.firstname,u.lastname,u.distid,sps.sponsees,u.usertype,u.is_tv_user,u.current_product_id
FROM users u,
( SELECT sp.sponsorid,
            count(*) AS sponsees
           FROM users sp
          GROUP BY sp.sponsorid) sps
  WHERE u.distid::text = sps.sponsorid::text;


CREATE VIEW vusersandaddresses AS
 SELECT u.id,u.firstname,u.lastname,u.email,u.phonenumber,u.account_status,u.email_verified,u.created_date,u.entered_by,u.username,u.basic_info_updated,
    u.distid,u.sponsorid,u.current_product_id,a.stateprov,a.countrycode,u.usertype
   FROM users u
     LEFT JOIN addresses a ON u.id = a.userid;


-- Stored Procedure: enrolment_tree
CREATE TYPE DISTRIBUTOR_2 AS (
	level integer,
    id  bigint,
    firstname character varying(50),
    lastname character varying(50),
	distid character varying(12),
	sponsorid character varying(50),
	email character varying(50),
	username character varying(50),
	current_product_id integer
);
CREATE FUNCTION enrolment_tree(dist_id varchar) RETURNS SETOF DISTRIBUTOR_2 AS
$BODY$
BEGIN
    RETURN QUERY WITH RECURSIVE distributors AS (
		 SELECT 0 as level,id,firstname,lastname,distid,sponsorid,email,username,current_product_id
		 FROM users
		 WHERE distid = dist_id
		 UNION
		 SELECT d.level+1,sp.id,sp.firstname,sp.lastname,sp.distid,sp.sponsorid,sp.email,sp.username,sp.current_product_id
		 FROM users sp
		 INNER JOIN distributors d ON d.distid = sp.sponsorid
		) SELECT
		 *
		FROM
		 distributors
		 where level<=7;

 END
$BODY$
LANGUAGE plpgsql;

CREATE VIEW vboomerangtrackerusers AS
SELECT
b.lead_firstname,b.lead_lastname,b.lead_email,b.lead_mobile,b.boomerang_code,b.date_created,
b.exp_dt,b.mode,b.group_campaign,b.group_no_of_uses,b.group_available,b.is_used,u.distid
FROM boomerang_tracker as b INNER JOIN users as u
ON u.id = b.userid

CREATE VIEW vproductsproducttype AS
SELECT p.id, p.productname, p.is_enabled, p.producttype,p.productdesc,
p.price,p.sku,p.itemcode,p.bv,p.cv,p.qv,p.num_boomerangs,p.sponsor_boomerangs,pt.typedesc
FROM products AS p LEFT JOIN producttype AS pt
ON p.producttype = pt.id

-- Distributors Count By Country
CREATE VIEW vdistributorsbycountry AS
SELECT a.countrycode, c.country, count(*) AS users_count
FROM addresses a
JOIN country c ON c.countrycode = a.countrycode
GROUP BY a.countrycode, c.country

-- pre_enrollment_selection | users | products
CREATE VIEW vpesusersproducts AS
SELECT pes.idecide_user,pes.saveon_user,pes.is_processed,pes.is_process_success,u.distid,p.productname
FROM pre_enrollment_selection AS pes
INNER JOIN users AS u ON pes."userId" = u.id
INNER JOIN products AS p ON pes."productId" = p.id

-- Total order sum for month
CREATE VIEW v_total_order_sum_for_month AS
SELECT created_date,
SUM (ordertotal) AS total_order_amount_sum
FROM orders
GROUP BY created_date;

-- Enrollments count by day
create view venrollmentsbyday as
SELECT u.created_date,
count(*) AS en_count
FROM users u
GROUP BY u.created_date;

-- Boomerang count by day
CREATE VIEW vboomerangcountbyday AS
SELECT date_created,
       COUNT(CASE WHEN mode = 1 THEN 1 END) AS ind_count,
       sum( CASE WHEN mode = 2 THEN group_no_of_uses END) AS grp_count
FROM boomerang_tracker
GROUP BY date_created

-- Update History And Users
CREATE VIEW vupdatehistory_users AS
SELECT uh.*, u.firstname || ' ' || u.lastname AS name
FROM update_history AS uh
INNER JOIN users AS u ON u.id = uh.updated_by

CREATE VIEW v_ewallet_transactions_users AS
SELECT et.id, et.remarks, et.amount, et.payap_mobile, et.created_at, et.withdraw_method, et.type, et.csv_generated, u.distid, u.firstname, u.lastname
FROM ewallet_transactions AS et
INNER JOIN users AS u ON et.user_id = u.id
--ALTER TABLE pre_enrollment_selection
--ADD idecide_user integer,
--ADD saveon_user integer,
--ADD is_processed integer,
--ADD is_process_success varchar,
--ADD process_msg varchar;

--  </ created by mahran >

CREATE VIEW v_discount_coupon AS
SELECT dc.*, b.distid
FROM discount_coupon AS dc LEFT JOIN users AS b
ON dc.used_by = b.id


CREATE VIEW v_ewallet_transactions AS
select u.distid, u.firstname, u.lastname, u.username, et.payap_mobile, et.amount, et.created_at, et.type, et.csv_generated, et.id as et_id
from ewallet_transactions et, users u
where et.user_id = u.id

CREATE VIEW v_ewallet_csv AS
select ec.*, CONCAT(u.firstname, ' ', u.lastname) as generated_by_name
from ewallet_csv ec, users u
where ec.generated_by = u.id


create view v_approved_commission as
select commission.transaction_date, commission.processed_date, level, amount, memo, users.distid, users.username
from commission, users
where commission.user_id = users.id

create view v_bulk_email as
select bulk_mails.*, users.firstname, users.lastname
from bulk_mails, users
where bulk_mails.sent_by = users.id

CREATE VIEW vcustomersusers AS
SELECT c.id,c.custid,c.name,c.email,c.mobile,c.boomerang_code,c.created_date,c.sor_default_password,u.distid
FROM customers c
JOIN users u ON c.userid = u.id;


CREATE OR REPLACE FUNCTION public.get_payment_success_summary(
	)
    RETURNS TABLE(tran_date date, method integer, total bigint)
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE
    ROWS 1000
AS $BODY$begin
return query select attempted_date as tran_date,pm.pay_method_type as method,count(*)
 as total
from subscription_history sh join payment_methods pm on sh.payment_method_id=pm.id
where sh.status=1
group by attempted_date,pay_method_type
order by attempted_date desc;
end;$BODY$;

ALTER FUNCTION public.get_payment_success_summary()
    OWNER TO postgres;

-- get_users_by_highest_achievement() Modified | Mahran
CREATE OR REPLACE FUNCTION public.get_users_by_highest_achievement(
	)
    RETURNS TABLE(user_id integer, firstname character varying, lastname character varying,
				  distid character varying, country_code character varying, email character varying,
				  phonenumber character varying, achieved_rank character varying,
				  created_dt timestamp without time zone)
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE
    ROWS 1000
AS $BODY$begin
return query
select h.users_id as user_id,u.firstname,u.lastname,u.distid, u.country_code, u.email, u.phonenumber, rd.rankdesc as achieved_rank,h.created_dt
from users u join rank_history h on u.id=h.users_id join ( select max(lifetime_rank) as lifetime_rank, users_id from rank_history where users_id in (select id from users where usertype=2) group by users_id) as sq
ON h.users_id=sq.users_id and h.lifetime_rank=sq.lifetime_rank join rank_definition rd on sq.lifetime_rank=rd.rankval;
end;$BODY$;

ALTER FUNCTION public.get_users_by_highest_achievement()
    OWNER TO postgres;


-- v_monthly_rank_achievers | Mahran
CREATE OR REPLACE VIEW public.v_monthly_rank_achievers AS
 SELECT u.distid,
    u.firstname,
    u.lastname,
    c.country,
    u.email,
    u.phonenumber,
    urh.monthly_qv,
    ha.achieved_rank,
    urh.period,
    urh.monthly_rank_desc
   FROM user_rank_history urh
     JOIN users u ON u.id = urh.user_id
     JOIN addresses a ON a.userid = urh.user_id
     JOIN country c ON c.countrycode::text = a.countrycode::text
     JOIN get_users_by_highest_achievement() ha(user_id, firstname, lastname, distid, country_code, email, phonenumber, achieved_rank, created_dt) ON urh.user_id = ha.user_id
  WHERE a."primary" = 1;

ALTER TABLE public.v_monthly_rank_achievers
    OWNER TO countnew444;


-- v_monthly_income_earnings | Mahran
CREATE OR REPLACE VIEW public.v_monthly_income_earnings AS
 SELECT u.distid,
    u.firstname,
    u.lastname,
    round(et_sum.monthly_total_amount::numeric, 2) AS monthly_total_amount,
    et_sum.month,
    et_sum.year,
    round(et_sum_total.total_amount::numeric, 2) AS total_amount
   FROM ( SELECT ewallet_transactions.user_id,
            sum(ewallet_transactions.amount) AS monthly_total_amount,
            date_part('month'::text, ewallet_transactions.created_at) AS month,
            date_part('year'::text, ewallet_transactions.created_at) AS year
           FROM ewallet_transactions
          WHERE ewallet_transactions.type::text = 'DEPOSIT'::text
          GROUP BY ewallet_transactions.user_id, (date_part('month'::text, ewallet_transactions.created_at)), (date_part('year'::text, ewallet_transactions.created_at))
          ORDER BY ewallet_transactions.user_id DESC) et_sum
     JOIN users u ON u.id = et_sum.user_id
     JOIN ( SELECT ewallet_transactions.user_id,
            sum(ewallet_transactions.amount) AS total_amount
           FROM ewallet_transactions
          WHERE ewallet_transactions.type::text = 'DEPOSIT'::text
          GROUP BY ewallet_transactions.user_id) et_sum_total ON et_sum.user_id = et_sum_total.user_id;

ALTER TABLE public.v_monthly_income_earnings
    OWNER TO postgres;

-- v_rank_advancement | Mahran
CREATE OR REPLACE VIEW public.v_rank_advancement AS
 SELECT rh.users_id,
    rd.rankdesc AS achieved_rank,
    date(rh.created_dt) AS created_dt,
    rh.created_dt AS created_datetime,
    u.distid,
    u.firstname,
    u.lastname,
    u.email,
    u.phonenumber,
    c.country,
	rh.lifetime_rank
   FROM rank_history rh
     JOIN users u ON u.id = rh.users_id
     JOIN addresses a ON a.userid = u.id
     JOIN country c ON a.countrycode::text = c.countrycode::text
     JOIN rank_definition rd ON rd.rankval = rh.lifetime_rank
  WHERE a."primary" = 1 AND rh.lifetime_rank <> 10;

ALTER TABLE public.v_rank_advancement
    OWNER TO postgres;


