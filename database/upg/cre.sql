--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: addresses; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE addresses (
    id integer NOT NULL,
    userid integer,
    addrtype character varying(10),
    "primary" integer,
    address1 character varying(50),
    address2 character varying(50),
    city character varying(25),
    stateprov character varying(50),
    stateprov_abbrev character varying(2),
    postalcode character varying(10),
    countrycode character varying(10),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


ALTER TABLE public.addresses OWNER TO postgres;

--
-- Name: boomerang_inv; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE boomerang_inv (
    id integer,
    userid integer,
    pending_tot integer,
    available_tot integer
);


ALTER TABLE public.boomerang_inv OWNER TO postgres;

--
-- Name: cctokens; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cctokens (
    id integer NOT NULL,
    "userID" integer,
    "primary" integer,
    deleted integer,
    token character varying(100)
);


ALTER TABLE public.cctokens OWNER TO postgres;

--
-- Name: doclibrary; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE doclibrary (
    id integer NOT NULL,
    docname character varying(50),
    doctype character varying(20),
    doclink character varying(50),
    doccategory character varying(20),
    statuscode integer,
    updated_at timestamp without time zone,
    created_at timestamp without time zone
);


ALTER TABLE public.doclibrary OWNER TO postgres;

--
-- Name: iq_credits; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE iq_credits (
    id integer,
    legacyid character varying(15),
    credit_amt money,
    bv integer,
    date_used timestamp without time zone
);


ALTER TABLE public.iq_credits OWNER TO postgres;

--
-- Name: last_login; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE last_login (
    id integer,
    userid integer,
    environment integer,
    login_dt timestamp without time zone
);


ALTER TABLE public.last_login OWNER TO postgres;

--
-- Name: legacyiq; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE legacyiq (
    id integer NOT NULL,
    "orderId" character varying(255),
    "userId" character varying(10),
    username character varying(100),
    firstname character varying(100),
    lastname character varying(100),
    email character varying(100),
    phonenumber character varying(100),
    mobilenumber character varying(100),
    link character varying(100),
    country character varying(50),
    product character varying(50),
    walletaddress character varying(100),
    typeofpurchase character varying(50),
    status character varying(50),
    total integer DEFAULT 0 NOT NULL,
    type character varying(50),
    type_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    flag boolean
);


ALTER TABLE public.legacyiq OWNER TO postgres;

--
-- Name: orderItem; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "orderItem" (
    id integer NOT NULL,
    orderid integer,
    productid integer,
    quantity integer,
    itemprice money,
    bv integer,
    qv integer,
    cv integer
);


ALTER TABLE public."orderItem" OWNER TO postgres;

--
-- Name: orders; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE orders (
    id integer NOT NULL,
    userid integer,
    statuscode integer,
    ordersubtotal money,
    ordertax money,
    ordertotal money,
    orderbv integer,
    orderqv integer,
    ordercv integer,
    trasnactionid character varying(50),
    updated_at timestamp without time zone,
    created_at timestamp without time zone,
    cctokensid integer,
    shipping_address_id integer
);


ALTER TABLE public.orders OWNER TO postgres;

--
-- Name: products; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE products (
    id integer,
    productname character varying(50),
    producttype integer,
    productdesc character varying(100),
    productdesc2 character varying(100),
    isautoship integer,
    statuscode integer,
    created_at timestamp without time zone DEFAULT now(),
    udated_at timestamp without time zone,
    price money,
    price_as money,
    price2 money,
    price3 money,
    sku character varying(20),
    itemcode character varying(20),
    bv integer,
    cv integer,
    qv integer
);


ALTER TABLE public.products OWNER TO postgres;

--
-- Name: producttype; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE producttype (
    id integer,
    typedesc character varying(25),
    statuscode integer
);


ALTER TABLE public.producttype OWNER TO postgres;

--
-- Name: recurring_billing; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE recurring_billing (
    id integer,
    userid integer,
    products_id integer,
    statuscode integer,
    attempts integer,
    created_at timestamp without time zone DEFAULT now(),
    nextbill_at timestamp without time zone,
    lastattempt_dt timestamp without time zone,
    nextattempt_dt timestamp without time zone
);


ALTER TABLE public.recurring_billing OWNER TO postgres;

--
-- Name: statuscode; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE statuscode (
    id integer,
    status_desc character varying(25)
);


ALTER TABLE public.statuscode OWNER TO postgres;

--
-- Name: statuscode_history; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE statuscode_history (
    id integer,
    userid integer,
    statuscode integer,
    created_dt timestamp without time zone
);


ALTER TABLE public.statuscode_history OWNER TO postgres;

--
-- Name: user; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "user" (
    id integer NOT NULL,
    firstname character(25),
    mi character(1),
    lastname character(25),
    email character(50),
    phonenumber character varying(20),
    username character(25),
    refname character varying(25),
    distid character(12),
    updated_at timestamp without time zone,
    created_at timestamp without time zone,
    usertype integer,
    statuscode integer,
    sponsorid integer,
    legacyid character varying(15),
    deleted integer,
    mobilenumber character varying(25),
    is_business integer,
    business_name character varying(50),
    ssn character varying(15),
    fid character varying(15)
);


ALTER TABLE public."user" OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY legacyiq.id;


--
-- Name: usertype; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usertype (
    id integer,
    typedesc character varying(20),
    statuscode integer
);


ALTER TABLE public.usertype OWNER TO postgres;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY legacyiq ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Name: USER_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "USER_pkey" PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY legacyiq
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: addresses; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE addresses FROM PUBLIC;
REVOKE ALL ON TABLE addresses FROM postgres;
GRANT ALL ON TABLE addresses TO postgres;
GRANT ALL ON TABLE addresses TO "postgres";


--
-- Name: boomerang_inv; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE boomerang_inv FROM PUBLIC;
REVOKE ALL ON TABLE boomerang_inv FROM postgres;
GRANT ALL ON TABLE boomerang_inv TO postgres;
GRANT ALL ON TABLE boomerang_inv TO "postgres";


--
-- Name: cctokens; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE cctokens FROM PUBLIC;
REVOKE ALL ON TABLE cctokens FROM postgres;
GRANT ALL ON TABLE cctokens TO postgres;
GRANT ALL ON TABLE cctokens TO "postgres";


--
-- Name: doclibrary; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE doclibrary FROM PUBLIC;
REVOKE ALL ON TABLE doclibrary FROM postgres;
GRANT ALL ON TABLE doclibrary TO postgres;
GRANT ALL ON TABLE doclibrary TO "postgres";


--
-- Name: iq_credits; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE iq_credits FROM PUBLIC;
REVOKE ALL ON TABLE iq_credits FROM postgres;
GRANT ALL ON TABLE iq_credits TO postgres;
GRANT ALL ON TABLE iq_credits TO "postgres";


--
-- Name: last_login; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE last_login FROM PUBLIC;
REVOKE ALL ON TABLE last_login FROM postgres;
GRANT ALL ON TABLE last_login TO postgres;
GRANT ALL ON TABLE last_login TO "postgres";


--
-- Name: legacyiq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE legacyiq FROM PUBLIC;
REVOKE ALL ON TABLE legacyiq FROM postgres;
GRANT ALL ON TABLE legacyiq TO postgres;
GRANT ALL ON TABLE legacyiq TO "postgres";


--
-- Name: orderItem; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE "orderItem" FROM PUBLIC;
REVOKE ALL ON TABLE "orderItem" FROM postgres;
GRANT ALL ON TABLE "orderItem" TO postgres;
GRANT ALL ON TABLE "orderItem" TO "postgres";


--
-- Name: orders; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE orders FROM PUBLIC;
REVOKE ALL ON TABLE orders FROM postgres;
GRANT ALL ON TABLE orders TO postgres;
GRANT ALL ON TABLE orders TO "postgres";


--
-- Name: products; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE products FROM PUBLIC;
REVOKE ALL ON TABLE products FROM postgres;
GRANT ALL ON TABLE products TO postgres;
GRANT ALL ON TABLE products TO "postgres";


--
-- Name: producttype; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE producttype FROM PUBLIC;
REVOKE ALL ON TABLE producttype FROM postgres;
GRANT ALL ON TABLE producttype TO postgres;
GRANT ALL ON TABLE producttype TO "postgres";


--
-- Name: recurring_billing; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE recurring_billing FROM PUBLIC;
REVOKE ALL ON TABLE recurring_billing FROM postgres;
GRANT ALL ON TABLE recurring_billing TO postgres;
GRANT ALL ON TABLE recurring_billing TO "postgres";


--
-- Name: statuscode; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE statuscode FROM PUBLIC;
REVOKE ALL ON TABLE statuscode FROM postgres;
GRANT ALL ON TABLE statuscode TO postgres;
GRANT ALL ON TABLE statuscode TO "postgres";


--
-- Name: statuscode_history; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE statuscode_history FROM PUBLIC;
REVOKE ALL ON TABLE statuscode_history FROM postgres;
GRANT ALL ON TABLE statuscode_history TO postgres;
GRANT ALL ON TABLE statuscode_history TO "postgres";


--
-- Name: user; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE "user" FROM PUBLIC;
REVOKE ALL ON TABLE "user" FROM postgres;
GRANT ALL ON TABLE "user" TO postgres;
GRANT ALL ON TABLE "user" TO "postgres";


--
-- Name: usertype; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usertype FROM PUBLIC;
REVOKE ALL ON TABLE usertype FROM postgres;
GRANT ALL ON TABLE usertype TO postgres;
GRANT ALL ON TABLE usertype TO "postgres";


--
-- PostgreSQL database dump complete
--

