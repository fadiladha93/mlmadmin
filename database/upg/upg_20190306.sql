alter table users
add column is_tv_user smallint default 0;

alter table pre_enrollment_selection
add column is_processed smallint default 0;

alter table pre_enrollment_selection
add column is_process_success smallint default 0;

alter table pre_enrollment_selection
add column process_msg text null;
