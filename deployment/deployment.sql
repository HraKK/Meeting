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
-- Name: events; Type: TABLE; Schema: public; Owner: denis; Tablespace:
--

CREATE TABLE events (
    id integer NOT NULL,
    room_id smallint,
    date_start timestamp without time zone,
    date_end timestamp without time zone,
    user_id integer,
    title character varying(30),
    description text,
    repeatable boolean,
    attendees smallint
);


ALTER TABLE public.events OWNER TO denis;

--
-- Name: events_id_seq; Type: SEQUENCE; Schema: public; Owner: denis
--

CREATE SEQUENCE events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.events_id_seq OWNER TO denis;

--
-- Name: events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: denis
--

ALTER SEQUENCE events_id_seq OWNED BY events.id;


--
-- Name: repeating_options; Type: TABLE; Schema: public; Owner: denis; Tablespace:
--

CREATE TABLE repeating_options (
    id integer NOT NULL,
    "1" boolean,
    "2" boolean,
    "3" boolean,
    "4" boolean,
    "5" boolean,
    "6" boolean,
    "7" boolean
);


ALTER TABLE public.repeating_options OWNER TO denis;

--
-- Name: TABLE repeating_options; Type: COMMENT; Schema: public; Owner: denis
--

COMMENT ON TABLE repeating_options IS 'days week';


--
-- Name: rooms; Type: TABLE; Schema: public; Owner: denis; Tablespace:
--

CREATE TABLE rooms (
    id integer NOT NULL,
    title character varying(30),
    description text,
    attendees smallint
);


ALTER TABLE public.rooms OWNER TO denis;

--
-- Name: rooms_id_seq; Type: SEQUENCE; Schema: public; Owner: denis
--

CREATE SEQUENCE rooms_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rooms_id_seq OWNER TO denis;

--
-- Name: rooms_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: denis
--

ALTER SEQUENCE rooms_id_seq OWNED BY rooms.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: denis; Tablespace:
--

CREATE TABLE users (
    id integer NOT NULL,
    name character varying(30),
    phone character varying(20),
    "position" character varying(40),
    nickname character varying(30)
);


ALTER TABLE public.users OWNER TO denis;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: denis
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO denis;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: denis
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: denis
--

ALTER TABLE ONLY events ALTER COLUMN id SET DEFAULT nextval('events_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: denis
--

ALTER TABLE ONLY rooms ALTER COLUMN id SET DEFAULT nextval('rooms_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: denis
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Name: events_pkey; Type: CONSTRAINT; Schema: public; Owner: denis; Tablespace:
--

ALTER TABLE ONLY events
    ADD CONSTRAINT events_pkey PRIMARY KEY (id);


--
-- Name: repeating_options_pkey; Type: CONSTRAINT; Schema: public; Owner: denis; Tablespace:
--

ALTER TABLE ONLY repeating_options
    ADD CONSTRAINT repeating_options_pkey PRIMARY KEY (id);


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


ALTER TABLE "public"."repeating_options"
ADD CONSTRAINT "event_id" FOREIGN KEY ("id") REFERENCES "public"."events" ("id") ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE "public"."events" RENAME "rooom_id" TO "room_id";

ALTER TABLE "public"."repeating_options" RENAME "1" TO "mon";
ALTER TABLE "public"."repeating_options" RENAME "2" TO "tue";
ALTER TABLE "public"."repeating_options" RENAME "3" TO "wed";
ALTER TABLE "public"."repeating_options" RENAME "4" TO "thu";
ALTER TABLE "public"."repeating_options" RENAME "5" TO "fri";
ALTER TABLE "public"."repeating_options" RENAME "6" TO "sat";
ALTER TABLE "public"."repeating_options" RENAME "7" TO "sun";


-- TEST DATA
INSERT INTO events
VALUES (4, 1, '2014-03-18 17:33:38', '2014-03-18 18:33:38', 1, 'zzsdfas', 'asdfcvxcz cv zxcvxz v', FALSE, 7);
INSERT INTO events
VALUES (1, 1, '2014-03-17 20:40:50.499974', '2014-03-17 21:50:50.499974', 1, 'meeting', 'meeting desc', TRUE, 3);

INSERT INTO rooms VALUES (1, 'Yellow room', 'big ', 10);
INSERT INTO rooms VALUES (2, 'Green room', 'small', 5);

INSERT INTO repeating_options VALUES (1, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE);


-- ANOTHER TEST DATA
INSERT INTO events
VALUES (6, 2, '2014-03-27 10:00:00', '2014-03-27 11:30:00', 1, 'zzsdfas', 'asdfcvxcz cv zxcvxz v', FALSE, 7);
INSERT INTO events
VALUES (7, 2, '2014-03-27 16:00:00', '2014-03-27 16:30:00', 1, 'meeting', 'meeting desc', TRUE, 3);