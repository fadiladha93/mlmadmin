

alter table public.users
add column password varchar(255) null;

alter table public.users
add column account_status varchar(20) default('APPROVED');

alter table public.users
add column email_verified smallint default(0);

alter table public.users
add column entered_by bigint default(0);

alter table public.users
add column basic_info_updated smallint default(0);

alter table public.users
add column remember_token varchar(100) null;

-- Table: public.promo_info

-- DROP TABLE public.promo_info;

CREATE TABLE public.promo_info
(
    id bigint NOT NULL DEFAULT nextval('promo_info_id_seq'::regclass),
    top_banner_img character varying(200) COLLATE pg_catalog."default",
    top_banner_url character varying(200) COLLATE pg_catalog."default",
    top_banner_is_active smallint,
    side_banner_img character varying(200) COLLATE pg_catalog."default",
    side_banner_title character varying(200) COLLATE pg_catalog."default",
    side_banner_short_desc text COLLATE pg_catalog."default",
    side_banner_long_desc text COLLATE pg_catalog."default",
    side_banner_is_active smallint,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.promo_info
    OWNER to postgres;

