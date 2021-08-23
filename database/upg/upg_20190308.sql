alter table boomerang_tracker
add column mode varchar;

alter table boomerang_tracker
add column is_used smallint;

alter table boomerang_tracker
add column lead_firstname varchar;

alter table boomerang_tracker
add column lead_lastname varchar;

alter table boomerang_tracker
add column lead_email varchar;

alter table boomerang_tracker
add column lead_mobile varchar;

alter table boomerang_tracker
add column group_campaign varchar;

alter table boomerang_tracker
add column group_no_of_uses number;

alter table boomerang_tracker
add column group_used number;
