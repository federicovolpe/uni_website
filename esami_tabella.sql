--
-- PostgreSQL database dump
--

-- Dumped from database version 14.5
-- Dumped by pg_dump version 14.5

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: esami; Type: TABLE; Schema: unimi_it; Owner: luigivolpe
--

CREATE TABLE unimi_it.esami (
    id character(6) NOT NULL,
    insegnamento character(10),
    docente character(6),
    data date
);


ALTER TABLE unimi_it.esami OWNER TO luigivolpe;

--
-- Data for Name: esami; Type: TABLE DATA; Schema: unimi_it; Owner: luigivolpe
--

COPY unimi_it.esami (id, insegnamento, docente, data) FROM stdin;
100001	9914411111	314434	2023-06-01
100002	1973869985	619231	2023-06-03
100003	5210097035	945514	2023-06-05
100004	3204759382	719958	2023-06-07
100005	2995667581	407789	2023-06-09
100006	3526956576	833919	2023-06-11
100007	5837527637	427844	2023-06-13
100008	1647855372	833919	2023-06-15
100009	8610762518	427844	2023-06-17
100010	7477422085	554198	2023-06-19
100011	3526956576	833919	2023-06-21
100012	5837527637	427844	2023-06-23
000001	7477422085	554198	2023-06-16
000002	6405967915	427844	2023-06-15
000003	9428798504	554198	2023-06-15
100013	6967310076	554198	2023-06-03
100016	9428798504	554198	2011-11-11
100017	9428798504	554198	2012-11-11
\.


--
-- Name: esami esami_pkey; Type: CONSTRAINT; Schema: unimi_it; Owner: luigivolpe
--

ALTER TABLE ONLY unimi_it.esami
    ADD CONSTRAINT esami_pkey PRIMARY KEY (id);


--
-- Name: esami before_insert_esami; Type: TRIGGER; Schema: unimi_it; Owner: luigivolpe
--

CREATE TRIGGER before_insert_esami BEFORE INSERT ON unimi_it.esami FOR EACH ROW EXECUTE FUNCTION unimi_it.generate_esami_id();


--
-- Name: esami responsabile_insegnamento_trigger; Type: TRIGGER; Schema: unimi_it; Owner: luigivolpe
--

CREATE TRIGGER responsabile_insegnamento_trigger BEFORE INSERT ON unimi_it.esami FOR EACH ROW EXECUTE FUNCTION unimi_it.check_responsabile_insegnamento();


--
-- Name: esami esami_docente_fkey; Type: FK CONSTRAINT; Schema: unimi_it; Owner: luigivolpe
--

ALTER TABLE ONLY unimi_it.esami
    ADD CONSTRAINT esami_docente_fkey FOREIGN KEY (docente) REFERENCES unimi_it.docente(id);


--
-- PostgreSQL database dump complete
--

