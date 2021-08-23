CREATE VIEW lead_intern AS
SELECT leads.name, leads.email, leads.phone, leads.contact_date, leads.status, CONCAT(users.first_name,' ',users.surname, ' (', users.email ,')') AS intern_detail
FROM leads, users
WHERE leads.added_by = users.id;

CREATE VIEW customer_intern AS
SELECT 
customers.id,
CONCAT(customers.first_name,' ',customers.last_name) AS customer_name, customers.email, 
CONCAT(customers.address1,' | ',customers.address2) AS address, 
CONCAT(customers.city,' | ',customers.state,' | ', customers.zip,' | ', customers.country) AS location, 
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
CONCAT(a.product_sku,' - ',a.product_name,' - ' ,a.product_price) AS product_info
FROM transactions a, customer_intern b
WHERE a.customer_id = b.id;

CREATE VIEW transaction_with_user AS
SELECT CONCAT(c.first_name,' ',c.last_name) AS customer_name, c.email, CONCAT(a.product_sku,' - ',a.product_name) AS product_info, a.product_price, a.total, b.id AS  user_id
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
--  </ created by mahran >