--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: phss; Type: DATABASE; Schema: -; Owner: phss
--

CREATE DATABASE phss WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'en_US.UTF-8' LC_CTYPE = 'en_US.UTF-8';


ALTER DATABASE phss OWNER TO phss;

\connect phss

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: search_hymns(text); Type: FUNCTION; Schema: public; Owner: phss
--

CREATE FUNCTION search_hymns(s text) RETURNS TABLE(hymn integer, title character varying, line text)
    LANGUAGE sql
    AS $$
SELECT h.number, h.title, he.el FROM ( SELECT hymn,unnest(element) AS el FROM hymn_elements) he INNER JOIN hymns h ON h.number = he.hymn WHERE lower(el) LIKE lower('%' || s || '%') UNION SELECT hymns.number, hymns.title, '' AS el FROM hymns WHERE lower(title) LIKE lower('%' || s || '%') ORDER BY title;
$$;


ALTER FUNCTION public.search_hymns(s text) OWNER TO phss;

--
-- Name: search_title(text); Type: FUNCTION; Schema: public; Owner: phss
--

CREATE FUNCTION search_title(s text) RETURNS TABLE(hymn integer, title character varying, line text)
    LANGUAGE sql
    AS $$
SELECT hymns.number, hymns.title, ''::text AS el FROM hymns WHERE lower(title) LIKE lower('%' || s || '%') ORDER BY title;
$$;


ALTER FUNCTION public.search_title(s text) OWNER TO phss;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: acct_group_members; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_group_members (
    grpid integer NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE acct_group_members OWNER TO phss;

--
-- Name: acct_group_roles; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_group_roles (
    grpid integer NOT NULL,
    role integer NOT NULL
);


ALTER TABLE acct_group_roles OWNER TO phss;

--
-- Name: acct_groups; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_groups (
    id integer NOT NULL,
    name character varying(128) NOT NULL
);


ALTER TABLE acct_groups OWNER TO phss;

--
-- Name: acct_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: phss
--

CREATE SEQUENCE acct_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE acct_groups_id_seq OWNER TO phss;

--
-- Name: acct_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phss
--

ALTER SEQUENCE acct_groups_id_seq OWNED BY acct_groups.id;


--
-- Name: acct_org_groups; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_org_groups (
    orgid integer NOT NULL,
    grpid integer NOT NULL
);


ALTER TABLE acct_org_groups OWNER TO phss;

--
-- Name: acct_org_members; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_org_members (
    uid integer NOT NULL,
    orgid integer NOT NULL
);


ALTER TABLE acct_org_members OWNER TO phss;

--
-- Name: acct_org_roles; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_org_roles (
    orgid integer NOT NULL,
    role integer NOT NULL
);


ALTER TABLE acct_org_roles OWNER TO phss;

--
-- Name: acct_orgs; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_orgs (
    id integer NOT NULL,
    name character varying(128) NOT NULL,
    poc integer,
    addr_line1 text,
    addr_line2 text,
    addr_city text,
    addr_state text,
    addr_zip text,
    addr_country text,
    phone text,
    email text,
    expire_date date
);


ALTER TABLE acct_orgs OWNER TO phss;

--
-- Name: acct_orgs_id_seq; Type: SEQUENCE; Schema: public; Owner: phss
--

CREATE SEQUENCE acct_orgs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE acct_orgs_id_seq OWNER TO phss;

--
-- Name: acct_orgs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phss
--

ALTER SEQUENCE acct_orgs_id_seq OWNED BY acct_orgs.id;


--
-- Name: acct_password_history; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_password_history (
    id integer,
    password text
);


ALTER TABLE acct_password_history OWNER TO phss;

--
-- Name: acct_roles; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_roles (
    id integer NOT NULL,
    name character varying(128),
    superuser boolean DEFAULT false,
    org_admin boolean DEFAULT false,
    download boolean DEFAULT true,
    gdrive boolean DEFAULT true,
    onedrive boolean DEFAULT true,
    widescreen boolean DEFAULT true,
    normal boolean DEFAULT true,
    edit_hymns boolean DEFAULT false,
    grp_admin boolean DEFAULT false
);


ALTER TABLE acct_roles OWNER TO phss;

--
-- Name: acct_roles_id_seq; Type: SEQUENCE; Schema: public; Owner: phss
--

CREATE SEQUENCE acct_roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE acct_roles_id_seq OWNER TO phss;

--
-- Name: acct_roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phss
--

ALTER SEQUENCE acct_roles_id_seq OWNED BY acct_roles.id;


--
-- Name: acct_user_roles; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_user_roles (
    uid integer NOT NULL,
    role integer NOT NULL
);


ALTER TABLE acct_user_roles OWNER TO phss;

--
-- Name: acct_users; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE acct_users (
    id integer NOT NULL,
    password text,
    password_changed timestamp without time zone,
    enabled boolean DEFAULT true,
    last_login timestamp without time zone,
    login_host inet,
    login_failed integer DEFAULT 0 NOT NULL,
    expire_date date,
    email text NOT NULL
);


ALTER TABLE acct_users OWNER TO phss;

--
-- Name: acct_users_id_seq; Type: SEQUENCE; Schema: public; Owner: phss
--

CREATE SEQUENCE acct_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE acct_users_id_seq OWNER TO phss;

--
-- Name: acct_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phss
--

ALTER SEQUENCE acct_users_id_seq OWNED BY acct_users.id;


--
-- Name: authors; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE authors (
    id integer NOT NULL,
    first character varying(128),
    middle character varying(128),
    last character varying(128) NOT NULL,
    suffix character varying(8)
);


ALTER TABLE authors OWNER TO phss;

--
-- Name: authors_id_seq; Type: SEQUENCE; Schema: public; Owner: phss
--

CREATE SEQUENCE authors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE authors_id_seq OWNER TO phss;

--
-- Name: authors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phss
--

ALTER SEQUENCE authors_id_seq OWNED BY authors.id;


--
-- Name: capture_sources; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE capture_sources (
    id integer NOT NULL,
    source character varying(128) NOT NULL,
    description text
);


ALTER TABLE capture_sources OWNER TO phss;

--
-- Name: capture_sources_id_seq; Type: SEQUENCE; Schema: public; Owner: phss
--

CREATE SEQUENCE capture_sources_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE capture_sources_id_seq OWNER TO phss;

--
-- Name: capture_sources_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phss
--

ALTER SEQUENCE capture_sources_id_seq OWNED BY capture_sources.id;


--
-- Name: composers; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE composers (
    id integer NOT NULL,
    first character varying(128),
    middle character varying(128),
    last character varying(128) NOT NULL,
    suffix character varying(8)
);


ALTER TABLE composers OWNER TO phss;

--
-- Name: composers_id_seq; Type: SEQUENCE; Schema: public; Owner: phss
--

CREATE SEQUENCE composers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE composers_id_seq OWNER TO phss;

--
-- Name: composers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phss
--

ALTER SEQUENCE composers_id_seq OWNED BY composers.id;


--
-- Name: hymn_addl_authors; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymn_addl_authors (
    hymn integer,
    author integer,
    role character varying(32),
    year integer
);


ALTER TABLE hymn_addl_authors OWNER TO phss;

--
-- Name: hymn_addl_composers; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymn_addl_composers (
    hymn integer,
    composer integer,
    role character varying(32),
    year integer
);


ALTER TABLE hymn_addl_composers OWNER TO phss;

--
-- Name: hymn_data; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymn_data (
    hymn integer,
    copyright text,
    key character varying(8),
    keyscale character varying(5),
    tune text,
    summary text,
    startnote character varying(3),
    timesignature character varying(5)
);


ALTER TABLE hymn_data OWNER TO phss;

--
-- Name: hymn_data_notes; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymn_data_notes (
    hymn integer,
    note text
);


ALTER TABLE hymn_data_notes OWNER TO phss;

--
-- Name: hymn_elements; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymn_elements (
    hymn integer NOT NULL,
    type character varying(16) NOT NULL,
    id integer NOT NULL,
    element character varying[] NOT NULL,
    elseq integer NOT NULL
);


ALTER TABLE hymn_elements OWNER TO phss;

--
-- Name: hymn_elements_img; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymn_elements_img (
    hymn integer NOT NULL,
    type character varying(16) NOT NULL,
    id integer NOT NULL,
    imgseq character varying(1) NOT NULL,
    elseq integer NOT NULL
);


ALTER TABLE hymn_elements_img OWNER TO phss;

--
-- Name: hymn_process; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymn_process (
    hymn integer,
    step character varying(16),
    initials character varying(3)
);


ALTER TABLE hymn_process OWNER TO phss;

--
-- Name: hymn_process_history; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymn_process_history (
    hymn integer,
    step character varying(16),
    steptime timestamp without time zone,
    initials character varying(3)
);


ALTER TABLE hymn_process_history OWNER TO phss;

--
-- Name: hymn_sources; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymn_sources (
    hymn integer,
    source integer
);


ALTER TABLE hymn_sources OWNER TO phss;

--
-- Name: hymn_topics; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymn_topics (
    hymn integer,
    topic integer
);


ALTER TABLE hymn_topics OWNER TO phss;

--
-- Name: hymns; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE hymns (
    number integer NOT NULL,
    ckey character varying(5) NOT NULL,
    title character varying(128) NOT NULL,
    author integer,
    composer integer,
    meter text,
    author_year integer,
    composer_year integer,
    author_role character varying(32),
    composer_role character varying(32)
);


ALTER TABLE hymns OWNER TO phss;

--
-- Name: topics; Type: TABLE; Schema: public; Owner: phss; Tablespace: 
--

CREATE TABLE topics (
    id integer NOT NULL,
    topic character varying(128) NOT NULL,
    description text
);


ALTER TABLE topics OWNER TO phss;

--
-- Name: topics_id_seq; Type: SEQUENCE; Schema: public; Owner: phss
--

CREATE SEQUENCE topics_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE topics_id_seq OWNER TO phss;

--
-- Name: topics_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phss
--

ALTER SEQUENCE topics_id_seq OWNED BY topics.id;


--
-- Name: v_hymns_author_composer; Type: VIEW; Schema: public; Owner: phss
--

CREATE VIEW v_hymns_author_composer AS
 SELECT hymns.number,
    hymns.title,
    hymns.meter,
    (((((authors.last)::text || ', '::text) || (authors.first)::text) || ' '::text) || (authors.middle)::text) AS author,
    hymns.author_year,
    hymns.author_role,
    (((((composers.last)::text || ', '::text) || (composers.first)::text) || ' '::text) || (composers.middle)::text) AS composer,
    hymns.composer_year,
    hymns.composer_role,
    hymn_data.copyright,
    hymn_data.key,
    hymn_data.keyscale,
    hymn_data.tune,
    hymn_data.summary,
    hymn_data.startnote,
    hymn_data.timesignature
   FROM (((hymns
     JOIN authors ON ((hymns.author = authors.id)))
     JOIN composers ON ((hymns.composer = composers.id)))
     JOIN hymn_data ON ((hymns.number = hymn_data.hymn)));


ALTER TABLE v_hymns_author_composer OWNER TO phss;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_groups ALTER COLUMN id SET DEFAULT nextval('acct_groups_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_orgs ALTER COLUMN id SET DEFAULT nextval('acct_orgs_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_roles ALTER COLUMN id SET DEFAULT nextval('acct_roles_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_users ALTER COLUMN id SET DEFAULT nextval('acct_users_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: phss
--

ALTER TABLE ONLY authors ALTER COLUMN id SET DEFAULT nextval('authors_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: phss
--

ALTER TABLE ONLY capture_sources ALTER COLUMN id SET DEFAULT nextval('capture_sources_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: phss
--

ALTER TABLE ONLY composers ALTER COLUMN id SET DEFAULT nextval('composers_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: phss
--

ALTER TABLE ONLY topics ALTER COLUMN id SET DEFAULT nextval('topics_id_seq'::regclass);


--
-- Name: acct_group_members_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_group_members
    ADD CONSTRAINT acct_group_members_pkey PRIMARY KEY (grpid, uid);


--
-- Name: acct_group_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_group_roles
    ADD CONSTRAINT acct_group_roles_pkey PRIMARY KEY (grpid, role);


--
-- Name: acct_groups_name_key; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_groups
    ADD CONSTRAINT acct_groups_name_key UNIQUE (name);


--
-- Name: acct_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_groups
    ADD CONSTRAINT acct_groups_pkey PRIMARY KEY (id);


--
-- Name: acct_org_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_org_groups
    ADD CONSTRAINT acct_org_groups_pkey PRIMARY KEY (orgid, grpid);


--
-- Name: acct_org_members_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_org_members
    ADD CONSTRAINT acct_org_members_pkey PRIMARY KEY (uid, orgid);


--
-- Name: acct_org_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_org_roles
    ADD CONSTRAINT acct_org_roles_pkey PRIMARY KEY (orgid, role);


--
-- Name: acct_orgs_name_key; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_orgs
    ADD CONSTRAINT acct_orgs_name_key UNIQUE (name);


--
-- Name: acct_orgs_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_orgs
    ADD CONSTRAINT acct_orgs_pkey PRIMARY KEY (id);


--
-- Name: acct_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_roles
    ADD CONSTRAINT acct_roles_pkey PRIMARY KEY (id);


--
-- Name: acct_user_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_user_roles
    ADD CONSTRAINT acct_user_roles_pkey PRIMARY KEY (uid, role);


--
-- Name: acct_users_email_key; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_users
    ADD CONSTRAINT acct_users_email_key UNIQUE (email);


--
-- Name: acct_users_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY acct_users
    ADD CONSTRAINT acct_users_pkey PRIMARY KEY (id);


--
-- Name: authors_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY authors
    ADD CONSTRAINT authors_pkey PRIMARY KEY (id);


--
-- Name: capture_sources_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY capture_sources
    ADD CONSTRAINT capture_sources_pkey PRIMARY KEY (id);


--
-- Name: capture_sources_source_key; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY capture_sources
    ADD CONSTRAINT capture_sources_source_key UNIQUE (source);


--
-- Name: composers_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY composers
    ADD CONSTRAINT composers_pkey PRIMARY KEY (id);


--
-- Name: hymn_elements_img2_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY hymn_elements_img
    ADD CONSTRAINT hymn_elements_img2_pkey PRIMARY KEY (hymn, type, id, imgseq);


--
-- Name: hymns_ckey_key; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY hymns
    ADD CONSTRAINT hymns_ckey_key UNIQUE (ckey);


--
-- Name: hymns_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY hymns
    ADD CONSTRAINT hymns_pkey PRIMARY KEY (number);


--
-- Name: topics_pkey; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY topics
    ADD CONSTRAINT topics_pkey PRIMARY KEY (id);


--
-- Name: topics_topic_key; Type: CONSTRAINT; Schema: public; Owner: phss; Tablespace: 
--

ALTER TABLE ONLY topics
    ADD CONSTRAINT topics_topic_key UNIQUE (topic);


--
-- Name: authors_idx; Type: INDEX; Schema: public; Owner: phss; Tablespace: 
--

CREATE UNIQUE INDEX authors_idx ON authors USING btree (first, middle, last, suffix);


--
-- Name: composers_idx; Type: INDEX; Schema: public; Owner: phss; Tablespace: 
--

CREATE UNIQUE INDEX composers_idx ON composers USING btree (first, middle, last, suffix);


--
-- Name: hymn_addl_authors_idx; Type: INDEX; Schema: public; Owner: phss; Tablespace: 
--

CREATE UNIQUE INDEX hymn_addl_authors_idx ON hymn_addl_authors USING btree (hymn, author);


--
-- Name: hymn_addl_composers_idx; Type: INDEX; Schema: public; Owner: phss; Tablespace: 
--

CREATE UNIQUE INDEX hymn_addl_composers_idx ON hymn_addl_composers USING btree (hymn, composer);


--
-- Name: hymn_data_idx; Type: INDEX; Schema: public; Owner: phss; Tablespace: 
--

CREATE UNIQUE INDEX hymn_data_idx ON hymn_data USING btree (hymn);


--
-- Name: hymn_elements_type_idx; Type: INDEX; Schema: public; Owner: phss; Tablespace: 
--

CREATE UNIQUE INDEX hymn_elements_type_idx ON hymn_elements USING btree (hymn, type, id);


--
-- Name: hymn_process_history_idx; Type: INDEX; Schema: public; Owner: phss; Tablespace: 
--

CREATE UNIQUE INDEX hymn_process_history_idx ON hymn_process_history USING btree (hymn, step, steptime, initials);


--
-- Name: hymn_process_idx; Type: INDEX; Schema: public; Owner: phss; Tablespace: 
--

CREATE UNIQUE INDEX hymn_process_idx ON hymn_process USING btree (hymn, step);


--
-- Name: hymn_topics_idx; Type: INDEX; Schema: public; Owner: phss; Tablespace: 
--

CREATE UNIQUE INDEX hymn_topics_idx ON hymn_topics USING btree (hymn, topic);


--
-- Name: acct_group_members_grpid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_group_members
    ADD CONSTRAINT acct_group_members_grpid_fkey FOREIGN KEY (grpid) REFERENCES acct_groups(id);


--
-- Name: acct_group_members_uid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_group_members
    ADD CONSTRAINT acct_group_members_uid_fkey FOREIGN KEY (uid) REFERENCES acct_users(id);


--
-- Name: acct_group_roles_grpid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_group_roles
    ADD CONSTRAINT acct_group_roles_grpid_fkey FOREIGN KEY (grpid) REFERENCES acct_groups(id);


--
-- Name: acct_group_roles_role_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_group_roles
    ADD CONSTRAINT acct_group_roles_role_fkey FOREIGN KEY (role) REFERENCES acct_roles(id);


--
-- Name: acct_org_groups_grpid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_org_groups
    ADD CONSTRAINT acct_org_groups_grpid_fkey FOREIGN KEY (grpid) REFERENCES acct_groups(id);


--
-- Name: acct_org_groups_orgid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_org_groups
    ADD CONSTRAINT acct_org_groups_orgid_fkey FOREIGN KEY (orgid) REFERENCES acct_orgs(id);


--
-- Name: acct_org_members_orgid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_org_members
    ADD CONSTRAINT acct_org_members_orgid_fkey FOREIGN KEY (orgid) REFERENCES acct_orgs(id);


--
-- Name: acct_org_members_uid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_org_members
    ADD CONSTRAINT acct_org_members_uid_fkey FOREIGN KEY (uid) REFERENCES acct_users(id);


--
-- Name: acct_org_roles_orgid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_org_roles
    ADD CONSTRAINT acct_org_roles_orgid_fkey FOREIGN KEY (orgid) REFERENCES acct_orgs(id);


--
-- Name: acct_org_roles_role_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_org_roles
    ADD CONSTRAINT acct_org_roles_role_fkey FOREIGN KEY (role) REFERENCES acct_roles(id);


--
-- Name: acct_orgs_poc_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_orgs
    ADD CONSTRAINT acct_orgs_poc_fkey FOREIGN KEY (poc) REFERENCES acct_users(id);


--
-- Name: acct_password_history_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_password_history
    ADD CONSTRAINT acct_password_history_id_fkey FOREIGN KEY (id) REFERENCES acct_users(id);


--
-- Name: acct_user_roles_role_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_user_roles
    ADD CONSTRAINT acct_user_roles_role_fkey FOREIGN KEY (role) REFERENCES acct_roles(id);


--
-- Name: acct_user_roles_uid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY acct_user_roles
    ADD CONSTRAINT acct_user_roles_uid_fkey FOREIGN KEY (uid) REFERENCES acct_users(id);


--
-- Name: hymn_addl_authors_author_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_addl_authors
    ADD CONSTRAINT hymn_addl_authors_author_fkey FOREIGN KEY (author) REFERENCES authors(id);


--
-- Name: hymn_addl_authors_hymn_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_addl_authors
    ADD CONSTRAINT hymn_addl_authors_hymn_fkey FOREIGN KEY (hymn) REFERENCES hymns(number);


--
-- Name: hymn_addl_composers_composer_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_addl_composers
    ADD CONSTRAINT hymn_addl_composers_composer_fkey FOREIGN KEY (composer) REFERENCES composers(id);


--
-- Name: hymn_addl_composers_hymn_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_addl_composers
    ADD CONSTRAINT hymn_addl_composers_hymn_fkey FOREIGN KEY (hymn) REFERENCES hymns(number);


--
-- Name: hymn_data_hymn_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_data
    ADD CONSTRAINT hymn_data_hymn_fkey FOREIGN KEY (hymn) REFERENCES hymns(number);


--
-- Name: hymn_data_notes_hymn_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_data_notes
    ADD CONSTRAINT hymn_data_notes_hymn_fkey FOREIGN KEY (hymn) REFERENCES hymns(number);


--
-- Name: hymn_elements_hymn_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_elements
    ADD CONSTRAINT hymn_elements_hymn_fkey FOREIGN KEY (hymn) REFERENCES hymns(number);


--
-- Name: hymn_elements_img2_hymn_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_elements_img
    ADD CONSTRAINT hymn_elements_img2_hymn_fkey FOREIGN KEY (hymn) REFERENCES hymns(number);


--
-- Name: hymn_process_history_hymn_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_process_history
    ADD CONSTRAINT hymn_process_history_hymn_fkey FOREIGN KEY (hymn) REFERENCES hymns(number);


--
-- Name: hymn_process_hymn_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_process
    ADD CONSTRAINT hymn_process_hymn_fkey FOREIGN KEY (hymn) REFERENCES hymns(number);


--
-- Name: hymn_sources_hymn_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_sources
    ADD CONSTRAINT hymn_sources_hymn_fkey FOREIGN KEY (hymn) REFERENCES hymns(number);


--
-- Name: hymn_sources_source_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_sources
    ADD CONSTRAINT hymn_sources_source_fkey FOREIGN KEY (source) REFERENCES capture_sources(id);


--
-- Name: hymn_topics_hymn_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_topics
    ADD CONSTRAINT hymn_topics_hymn_fkey FOREIGN KEY (hymn) REFERENCES hymns(number);


--
-- Name: hymn_topics_topic_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymn_topics
    ADD CONSTRAINT hymn_topics_topic_fkey FOREIGN KEY (topic) REFERENCES topics(id);


--
-- Name: hymns_author_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymns
    ADD CONSTRAINT hymns_author_fkey FOREIGN KEY (author) REFERENCES authors(id);


--
-- Name: hymns_composer_fkey; Type: FK CONSTRAINT; Schema: public; Owner: phss
--

ALTER TABLE ONLY hymns
    ADD CONSTRAINT hymns_composer_fkey FOREIGN KEY (composer) REFERENCES composers(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

