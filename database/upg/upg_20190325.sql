alter table users
add column admin_role integer;

update users set admin_role = 0;

update users set admin_role = 1
where usertype = 1;


