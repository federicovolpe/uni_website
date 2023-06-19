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

--
-- Name: unimi_it; Type: SCHEMA; Schema: -; 
--

CREATE SCHEMA unimi_it;


ALTER SCHEMA unimi_it ;

--
-- Name: verifica(text, text, text); Type: FUNCTION; Schema: public; 
--

CREATE FUNCTION public.verifica(email text, passwrd text, tipologia text) RETURNS integer
    LANGUAGE plpgsql
    AS $$
    DECLARE
        verificato integer;
    BEGIN
        SELECT 1 INTO verificato
        FROM tipologia
        WHERE email = email AND passwrd = passwrd;
        RETURN verificato;
    END;
$$;


ALTER FUNCTION public.verifica(email text, passwrd text, tipologia text) ;

--
-- Name: cancellazione_studente(); Type: FUNCTION; Schema: unimi_it; 
--

CREATE FUNCTION unimi_it.cancellazione_studente() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        -- INSERZIONE dei valori della tabella studente nello storico
        INSERT INTO storico_studente (matricola, nome, cognome, email, passwrd, corso_frequentato)
        VALUES (OLD.matricola, OLD.nome, OLD.cognome, OLD.email, OLD.passwrd, OLD.corso_frequentato);

        RETURN OLD;
    END;
    $$;


ALTER FUNCTION unimi_it.cancellazione_studente() ;

--
-- Name: check_responsabile_insegnamento(); Type: FUNCTION; Schema: unimi_it; 
--

CREATE FUNCTION unimi_it.check_responsabile_insegnamento() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
  -- Check if the docente and insegnamento exist in the responsabile_insegnamento table
  IF NOT EXISTS (
    SELECT 1
    FROM responsabile_insegnamento
    WHERE docente = NEW.docente AND insegnamento = NEW.insegnamento
  ) THEN
    RAISE EXCEPTION 'Il docente specificato non risulta responsabile dell insegnamento.';
  END IF;
  
  RETURN NEW;
END;
$$;


ALTER FUNCTION unimi_it.check_responsabile_insegnamento() ;

--
-- Name: controllo_propedeutici(); Type: FUNCTION; Schema: unimi_it; 
--

CREATE FUNCTION unimi_it.controllo_propedeutici() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
    IF EXISTS ( --SE L'ESAME HA DELLE PROPEDEUTICITà CONTROLLO CHE SIANO STATE SUPERATE
            SELECT 1
            FROM propedeuticità AS P
            WHERE P.insegnamento IN (
                SELECT I.id
                FROM esami AS E
                LEFT JOIN insegnamento AS I ON I.id = E.insegnamento
                WHERE E.id = NEW.esame
            )
        )
        THEN -- CONTROLLO CHE TUTTE LE PROPEDEUTICITà SIANO STATE PASSATE
            IF (SELECT COUNT(*) = (SELECT COUNT(*) -- conto delle righe della tabella propedeuticità che hanno un insegnamento presente nella tabella...
                                    FROM propedeuticità AS P
                                    WHERE P.insegnamento IN (SELECT I.id -- ...degli insegnamenti corrispondenti all'esame selezionato
                                                            FROM esami AS E
                                                            LEFT JOIN insegnamento AS I ON I.id = E.insegnamento
                                                            WHERE E.id = NEW.esame)
                                    ) AS all_tuples_present
                FROM propedeuticità AS P
                WHERE P.insegnamento IN (SELECT I.id --tabella degli insegnamenti per cui lo studente ha passato l'esame
                                        FROM esiti AS E
                                        JOIN esami AS Es ON E.esame = Es.id
                                        JOIN insegnamento AS I ON I.id = Es.insegnamento
                                        WHERE E.studente = NEW.studente AND E.esito >= 18)
                )
            THEN -- se le propedeuticità sono state passate allora procedo con l'inserimento
                RETURN NEW;
            ELSE -- se le propedeuticità non sono state tutte passate allora lo segnalo con un errore
                RAISE EXCEPTION 'Lo studente % non risulta aver superato tutti gli esami propedeutici', NEW.studente;
            END IF;
    ELSE -- se l'insegnamento non risulta avere propedeuticità allora posso procedere con l'inserimento
        RETURN NEW;
    END IF;
        
    END;
    $$;


ALTER FUNCTION unimi_it.controllo_propedeutici() ;

--
-- Name: elimina_responsabilità_corso(); Type: FUNCTION; Schema: unimi_it; 
--

CREATE FUNCTION unimi_it."elimina_responsabilità_corso"() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
        BEGIN
            DELETE FROM responsabile_corso
            WHERE corso = OLD.id;
            RETURN OLD;
        END;
    $$;


ALTER FUNCTION unimi_it."elimina_responsabilità_corso"() ;

--
-- Name: elimina_responsabilità_docente(); Type: FUNCTION; Schema: unimi_it; 
--

CREATE FUNCTION unimi_it."elimina_responsabilità_docente"() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
        BEGIN
            DELETE FROM responsabile_corso
            WHERE docente = OLD.id;
            RETURN OLD;
        END;
    $$;


ALTER FUNCTION unimi_it."elimina_responsabilità_docente"() ;

--
-- Name: generate_esami_id(); Type: FUNCTION; Schema: unimi_it; 
--

CREATE FUNCTION unimi_it.generate_esami_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    last_id CHAR(6);
    new_id INTEGER;
BEGIN
    SELECT MAX(id) INTO last_id FROM esami;
    
    IF last_id IS NULL THEN
        NEW.id := '000001';
    ELSE
        new_id := CAST(last_id AS INTEGER) + 1;
        NEW.id := LPAD(CAST(new_id AS VARCHAR), 6, '0');
    END IF;
    
    RETURN NEW;
END;
$$;


ALTER FUNCTION unimi_it.generate_esami_id() ;

--
-- Name: n_insegnamenti_responsabile(); Type: FUNCTION; Schema: unimi_it; 
--

CREATE FUNCTION unimi_it.n_insegnamenti_responsabile() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    DECLARE 
        conta_docente INTEGER;
    BEGIN
        SELECT COUNT(*) INTO conta_docente
        FROM responsabile_insegnamento
        WHERE docente = NEW.docente;

        IF (conta_docente > 3) THEN 
            RAISE EXCEPTION 'Il docente non può essere responsabile di più di 3 insegnamenti';
        END IF;

        RETURN NEW;
    END;
    $$;


ALTER FUNCTION unimi_it.n_insegnamenti_responsabile() ;

--
-- Name: salvataggio_esiti(); Type: FUNCTION; Schema: unimi_it; 
--

CREATE FUNCTION unimi_it.salvataggio_esiti() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
        BEGIN
            INSERT INTO storico_carriera (studente, esame, esito)
            VALUES (OLD.studente, OLD.esame, OLD.esito);
            RETURN OLD;
        END;
    $$;


ALTER FUNCTION unimi_it.salvataggio_esiti() ;

--
-- Name: verifica_iscrizione(); Type: FUNCTION; Schema: unimi_it; 
--

CREATE FUNCTION unimi_it.verifica_iscrizione() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS (
            SELECT studente 
            FROM iscrizioni 
            WHERE studente = NEW.studente AND esame = NEW.esame
        ) THEN
            RETURN NEW;
        ELSE
            RAISE EXCEPTION 'Lo studente % non risulta iscritto all''esame %', NEW.studente, NEW.esame;
        END IF;
    END;
    $$;


ALTER FUNCTION unimi_it.verifica_iscrizione() ;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: corso; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it.corso (
    id character(10) NOT NULL,
    nome_corso character varying(100) NOT NULL,
    laurea character varying(10),
    descrizione text,
    responsabile character(6),
    CONSTRAINT laurea CHECK ((((laurea)::text = 'triennale'::text) OR ((laurea)::text = 'magistrale'::text)))
);


ALTER TABLE unimi_it.corso ;

--
-- Name: docente; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it.docente (
    id character(6) NOT NULL,
    email character varying(100) NOT NULL,
    passwrd character varying(20) NOT NULL,
    nome character varying(20) NOT NULL,
    cognome character varying(20) NOT NULL
);


ALTER TABLE unimi_it.docente ;

--
-- Name: esami; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it.esami (
    id character(6) NOT NULL,
    insegnamento character(10),
    docente character(6),
    data date
);


ALTER TABLE unimi_it.esami ;

--
-- Name: esiti; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it.esiti (
    studente character(6),
    esame character(6),
    esito numeric NOT NULL
);


ALTER TABLE unimi_it.esiti ;

--
-- Name: insegnamento; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it.insegnamento (
    id character(10) NOT NULL,
    nome character varying(100) NOT NULL,
    descrizione text,
    anno character(4) NOT NULL,
    corso character(10) NOT NULL,
    cfu numeric,
    responsabile character(6)
);


ALTER TABLE unimi_it.insegnamento ;

--
-- Name: iscrizioni; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it.iscrizioni (
    studente character(6) NOT NULL,
    esame character(6) NOT NULL
);


ALTER TABLE unimi_it.iscrizioni ;

--
-- Name: propedeuticità; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it."propedeuticità" (
    insegnamento character(10),
    propedeutico character(10)
);


ALTER TABLE unimi_it."propedeuticità" ;

--
-- Name: segreteria; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it.segreteria (
    id character(6) NOT NULL,
    email character varying(100),
    passwrd character varying(20) NOT NULL,
    nome character varying(20) NOT NULL,
    cognome character varying(20) NOT NULL
);


ALTER TABLE unimi_it.segreteria ;

--
-- Name: storico_carriera; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it.storico_carriera (
    studente character(6) NOT NULL,
    esame character(6) NOT NULL,
    esito numeric NOT NULL,
    CONSTRAINT storico_carriera_esito_check CHECK (((esito >= (0)::numeric) AND (esito <= (30)::numeric)))
);


ALTER TABLE unimi_it.storico_carriera ;

--
-- Name: storico_studente; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it.storico_studente (
    matricola character(6) NOT NULL,
    nome character varying(20) NOT NULL,
    cognome character varying(20) NOT NULL,
    email character varying(100) NOT NULL,
    passwrd character varying(20) NOT NULL,
    corso_frequentato character(100) NOT NULL,
    CONSTRAINT email CHECK (((email)::text ~~ '%@studenti.unimi.it'::text))
);


ALTER TABLE unimi_it.storico_studente ;

--
-- Name: studente; Type: TABLE; Schema: unimi_it; 
--

CREATE TABLE unimi_it.studente (
    matricola character(6) NOT NULL,
    nome character varying(20) NOT NULL,
    cognome character varying(20) NOT NULL,
    email character varying(100) NOT NULL,
    passwrd character varying(20) NOT NULL,
    corso_frequentato character(100) NOT NULL
);


ALTER TABLE unimi_it.studente ;

--
-- Data for Name: corso; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it.corso (id, nome_corso, laurea, descrizione, responsabile) FROM stdin;
9175738010	Sicurezza Informatica	magistrale	Descrizione del corso Sicurezza Informatica:\n                                   Questo corso si focalizza sulla sicurezza delle reti informatiche...	719958
2124169738	Chimica	triennale	Descrizione del corso Chimica:\n                                   Questo corso fornisce una panoramica completa dei principi fondamentali...	407789
9202226989	Informatica Musicale	triennale	Descrizione del corso Informatica Musicale:\n                                   Questo corso combina le discipline di informatica e musica...	945514
3759187480	Fisica	triennale	Descrizione del corso Fisica:\n                                   Questo corso copre i principali argomenti della fisica moderna...	314434
7028895757	Informatica	triennale	Descrizione del corso Informatica:\n                                   Questo corso introduce gli studenti ai fondamenti dell informatica...	619231
\.


--
-- Data for Name: docente; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it.docente (id, email, passwrd, nome, cognome) FROM stdin;
314434	bernardine@docenti.unimi.it	password1	Bernardine	Harniman
619231	husein@docenti.unimi.it	password2	Husein	Dugue
945514	leigh@docenti.unimi.it	password3	Leigh	Bakster
719958	shaughn@docenti.unimi.it	password4	Shaughn	Fouracres
407789	lina@docenti.unimi.it	password5	Lina	Somerlie
991385	guthrie@docenti.unimi.it	password6	Guthrie	Rove
151049	galven@docenti.unimi.it	password7	Galven	Scothorne
833919	dalia@docenti.unimi.it	password8	Dalia	Izaac
427844	elwyn@docenti.unimi.it	password9	Elwyn	Renneke
554198	buddie@docenti.unimi.it	a	Buddie	MacCart
\.


--
-- Data for Name: esami; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it.esami (id, insegnamento, docente, data) FROM stdin;
100013	6967310076	554198	2010-10-22
100018	1193746368	554198	2011-01-10
100019	7477422085	554198	2023-10-10
100001	9914411111	314434	2023-06-01
100002	1973869985	619231	2023-06-03
100003	5210097035	945514	2023-06-05
100004	3204759382	719958	2023-06-07
100005	2995667581	407789	2023-06-09
100006	3526956576	833919	2023-06-11
100007	5837527637	427844	2023-06-13
100008	1647855372	833919	2023-06-15
100009	8610762518	427844	2023-06-17
100011	3526956576	833919	2023-06-21
100012	5837527637	427844	2023-06-23
000002	6405967915	427844	2023-06-15
000003	9428798504	554198	2023-06-15
100016	9428798504	554198	2011-11-11
100017	9428798504	554198	2012-11-11
\.


--
-- Data for Name: esiti; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it.esiti (studente, esame, esito) FROM stdin;
966031	100001	18
966031	000004	17
966031	000004	25
966031	000003	18
966031	000002	17
966031	100010	1
966031	100010	1
966031	100019	19
966031	100019	4
\.


--
-- Data for Name: insegnamento; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it.insegnamento (id, nome, descrizione, anno, corso, cfu, responsabile) FROM stdin;
2995667581	Information management	Descrizione dell insegnamento Information management: Un corso che fornisce...	2   	9175738010	12	407789
5837527637	Chimica generale	Descrizione dell insegnamento Chimica generale: Questo insegnamento copre...	2   	2124169738	12	427844
8650908123	Elettronica 1	Descrizione dell insegnamento Elettronica 1: Un corso che introduce...	1   	3759187480	12	833919
4789640943	Geometria	Descrizione dell insegnamento Geometria: Questo insegnamento offre una...	1   	3759187480	12	554198
2852290568	Chimica organica	Descrizione dell insegnamento Chimica organica: Un corso che...	1   	2124169738	6	427844
6967310076	Chimica 1	Descrizione dell insegnamento Chimica 1: Un corso introduttivo sulla...	2   	3759187480	6	554198
7477422085	Sistemi embedded	Descrizione dell insegnamento Sistemi embedded: Questo insegnamento...	3   	7028895757	6	554198
1655851251	Acustica	Descrizione dell insegnamento Acustica: Un corso che esplora...	2   	9202226989	6	554198
4104514266	Informatica del suono	Descrizione dell insegnamento Informatica del suono: Questo insegnamento...	3   	9202226989	6	554198
1973869985	Artificial intelligence	Descrizione dell insegnamento Artificial intelligence: Questo corso...	1   	9175738010	3	619231
5210097035	Componenti di biometria	Descrizione dell insegnamento Componenti di biometria: Un corso...	2   	9175738010	3	945514
3204759382	Crittografia	Descrizione dell insegnamento Crittografia: Un corso che...	1   	9175738010	3	719958
1193746368	Istituzioni di matematica	Descrizione dell insegnamento Istituzioni di matematica: Un...	1   	2124169738	3	554198
4864051855	Fisica quantistica	Descrizione dell insegnamento Fisica quantistica: Questo insegnamento...	3   	3759187480	3	427844
1402416704	Astrofisica	Descrizione dell insegnamento Astrofisica: Un corso avanzato...	2   	3759187480	3	833919
7037905580	Editoria digitale	Descrizione dell insegnamento Editoria digitale: Questo insegnamento...	2   	9202226989	3	833919
6684943179	Crittografia sonora	Descrizione dell insegnamento Crittografia sonora: Un corso che...	3   	9202226989	3	427844
8610762518	Informazione multimediale	Descrizione dell insegnamento Informazione multimediale: Questo insegnamento...	1   	9202226989	3	427844
9914411111	Affidabilità dei sistemi	Descrizione dell insegnamento Affidabilità dei sistemi: Un corso...	2   	9175738010	9	314434
3526956576	Fisica generale	Descrizione dell insegnamento Fisica generale: Questo insegnamento...	2   	2124169738	9	833919
7393377009	Chimica analitica	Descrizione dell insegnamento Chimica analitica	2   	2124169738	9	833919
5193752943	Logica	\N	3   	7028895757	9	427844
2485318062	Basi di dati	\N	3   	7028895757	9	833919
6405967915	Programmazione 1	\N	1   	7028895757	9	427844
9428798504	Programmazione 2	\N	2   	7028895757	9	554198
1647855372	Algoritmi e strutture dati	\N	2   	9202226989	9	833919
\.


--
-- Data for Name: iscrizioni; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it.iscrizioni (studente, esame) FROM stdin;
657874	000002
986899	100001
147692	000002
966031	100019
966031	000002
\.


--
-- Data for Name: propedeuticità; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it."propedeuticità" (insegnamento, propedeutico) FROM stdin;
9428798504	6405967915
\.


--
-- Data for Name: segreteria; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it.segreteria (id, email, passwrd, nome, cognome) FROM stdin;
000002	bob@segreteria.unimi.it	password2	Bob	Johnson
000003	charlie@segreteria.unimi.it	password3	Charlie	Brown
000004	diana@segreteria.unimi.it	password4	Diana	Wilson
000005	ethan@segreteria.unimi.it	password5	Ethan	Davis
000001	alice@segreteria.unimi.it	a	Alice	Smith
\.


--
-- Data for Name: storico_carriera; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it.storico_carriera (studente, esame, esito) FROM stdin;
\.


--
-- Data for Name: storico_studente; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it.storico_studente (matricola, nome, cognome, email, passwrd, corso_frequentato) FROM stdin;
000001	modifica	modifica	prova@studenti.unimi.it	slòasdkf	9175738010                                                                                          
\.


--
-- Data for Name: studente; Type: TABLE DATA; Schema: unimi_it; 
--

COPY unimi_it.studente (matricola, nome, cognome, email, passwrd, corso_frequentato) FROM stdin;
986899	Ancell	Janssens	ancell@studenti.unimi.it	password1	9175738010                                                                                          
103246	Clara	Poyner	clara@studenti.unimi.it	password2	9175738010                                                                                          
148008	Karyn	Claybourn	karyn@studenti.unimi.it	password3	2124169738                                                                                          
511415	Harmony	MacClenan	harmony@studenti.unimi.it	password4	2124169738                                                                                          
903187	Raine	Di Carli	raine@studenti.unimi.it	password5	2124169738                                                                                          
722630	Anissa	Guitel	anissa@studenti.unimi.it	password6	3759187480                                                                                          
358096	Vernen	Moss	vernen@studenti.unimi.it	password7	3759187480                                                                                          
208078	Annabal	Pamment	annabal@studenti.unimi.it	password8	3759187480                                                                                          
147692	Brigit	Jain	brigit@studenti.unimi.it	password9	7028895757                                                                                          
905716	Dena	Barringer	dena@studenti.unimi.it	password10	7028895757                                                                                          
657874	Willetta	Abrahami	willetta@studenti.unimi.it	password11	7028895757                                                                                          
571848	Gardner	Strand	gardner@studenti.unimi.it	password12	7028895757                                                                                          
645198	Worden	Gilhouley	worden@studenti.unimi.it	password13	9202226989                                                                                          
342631	Bibi	Huskinson	bibi@studenti.unimi.it	password14	9202226989                                                                                          
596144	Madlin	Findley	madlin@studenti.unimi.it	password15	9202226989                                                                                          
966031	federico	Volpe	federico.volpe@studenti.unimi.it	a	7028895757                                                                                          
\.


--
-- Name: corso corso_pkey; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.corso
    ADD CONSTRAINT corso_pkey PRIMARY KEY (id);


--
-- Name: docente docente_email_key; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.docente
    ADD CONSTRAINT docente_email_key UNIQUE (email);


--
-- Name: docente docente_pkey; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.docente
    ADD CONSTRAINT docente_pkey PRIMARY KEY (id);


--
-- Name: esami esami_pkey; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.esami
    ADD CONSTRAINT esami_pkey PRIMARY KEY (id);


--
-- Name: insegnamento insegnamento_nome_key; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.insegnamento
    ADD CONSTRAINT insegnamento_nome_key UNIQUE (nome);


--
-- Name: insegnamento insegnamento_pkey; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.insegnamento
    ADD CONSTRAINT insegnamento_pkey PRIMARY KEY (id);


--
-- Name: iscrizioni iscrizioni_pkey; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.iscrizioni
    ADD CONSTRAINT iscrizioni_pkey PRIMARY KEY (studente, esame);


--
-- Name: corso nome_corso_unico; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.corso
    ADD CONSTRAINT nome_corso_unico UNIQUE (nome_corso);


--
-- Name: segreteria segreteria_email_key; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.segreteria
    ADD CONSTRAINT segreteria_email_key UNIQUE (email);


--
-- Name: segreteria segreteria_pkey; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.segreteria
    ADD CONSTRAINT segreteria_pkey PRIMARY KEY (id);


--
-- Name: storico_carriera storico_carriera_pkey; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.storico_carriera
    ADD CONSTRAINT storico_carriera_pkey PRIMARY KEY (studente, esame);


--
-- Name: storico_studente storico_studente_email_key; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.storico_studente
    ADD CONSTRAINT storico_studente_email_key UNIQUE (email);


--
-- Name: storico_studente storico_studente_pkey; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.storico_studente
    ADD CONSTRAINT storico_studente_pkey PRIMARY KEY (matricola);


--
-- Name: studente studente_email_key; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.studente
    ADD CONSTRAINT studente_email_key UNIQUE (email);


--
-- Name: studente studente_pkey; Type: CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.studente
    ADD CONSTRAINT studente_pkey PRIMARY KEY (matricola);


--
-- Name: esami before_insert_esami; Type: TRIGGER; Schema: unimi_it; 
--

CREATE TRIGGER before_insert_esami BEFORE INSERT ON unimi_it.esami FOR EACH ROW EXECUTE FUNCTION unimi_it.generate_esami_id();


--
-- Name: studente cancellazione_studente_trigger; Type: TRIGGER; Schema: unimi_it; 
--

CREATE TRIGGER cancellazione_studente_trigger BEFORE DELETE ON unimi_it.studente FOR EACH ROW EXECUTE FUNCTION unimi_it.cancellazione_studente();


--
-- Name: iscrizioni controllo_propedeutici_trigger; Type: TRIGGER; Schema: unimi_it; 
--

CREATE TRIGGER controllo_propedeutici_trigger BEFORE INSERT ON unimi_it.iscrizioni FOR EACH ROW EXECUTE FUNCTION unimi_it.controllo_propedeutici();


--
-- Name: corso elimina_responsabilità_corso_trigger; Type: TRIGGER; Schema: unimi_it; 
--

CREATE TRIGGER "elimina_responsabilità_corso_trigger" BEFORE DELETE ON unimi_it.corso FOR EACH ROW EXECUTE FUNCTION unimi_it."elimina_responsabilità_corso"();


--
-- Name: docente elimina_responsabilità_docente_trigger; Type: TRIGGER; Schema: unimi_it; 
--

CREATE TRIGGER "elimina_responsabilità_docente_trigger" BEFORE DELETE ON unimi_it.docente FOR EACH ROW EXECUTE FUNCTION unimi_it."elimina_responsabilità_docente"();


--
-- Name: esami responsabile_insegnamento_trigger; Type: TRIGGER; Schema: unimi_it; 
--

CREATE TRIGGER responsabile_insegnamento_trigger BEFORE INSERT ON unimi_it.esami FOR EACH ROW EXECUTE FUNCTION unimi_it.check_responsabile_insegnamento();


--
-- Name: esiti salvataggio_esiti_trigger; Type: TRIGGER; Schema: unimi_it; 
--

CREATE TRIGGER salvataggio_esiti_trigger BEFORE DELETE ON unimi_it.esiti FOR EACH ROW EXECUTE FUNCTION unimi_it.salvataggio_esiti();


--
-- Name: esiti verifica_iscrizione_trigger; Type: TRIGGER; Schema: unimi_it; 
--

CREATE TRIGGER verifica_iscrizione_trigger BEFORE INSERT ON unimi_it.esiti FOR EACH ROW EXECUTE FUNCTION unimi_it.verifica_iscrizione();


--
-- Name: esiti cascado; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.esiti
    ADD CONSTRAINT cascado FOREIGN KEY (studente) REFERENCES unimi_it.studente(matricola) ON DELETE CASCADE;


--
-- Name: iscrizioni cascado; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.iscrizioni
    ADD CONSTRAINT cascado FOREIGN KEY (studente) REFERENCES unimi_it.studente(matricola) ON DELETE CASCADE;


--
-- Name: corso corso_responsabile_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.corso
    ADD CONSTRAINT corso_responsabile_fkey FOREIGN KEY (responsabile) REFERENCES unimi_it.docente(id) ON DELETE CASCADE;


--
-- Name: esami esami_docente_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.esami
    ADD CONSTRAINT esami_docente_fkey FOREIGN KEY (docente) REFERENCES unimi_it.docente(id);


--
-- Name: insegnamento insegnamento_corso_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.insegnamento
    ADD CONSTRAINT insegnamento_corso_fkey FOREIGN KEY (corso) REFERENCES unimi_it.corso(id);


--
-- Name: insegnamento insegnamento_responsabile_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.insegnamento
    ADD CONSTRAINT insegnamento_responsabile_fkey FOREIGN KEY (responsabile) REFERENCES unimi_it.docente(id) ON DELETE CASCADE;


--
-- Name: iscrizioni iscrizioni_esame_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.iscrizioni
    ADD CONSTRAINT iscrizioni_esame_fkey FOREIGN KEY (esame) REFERENCES unimi_it.esami(id) ON DELETE CASCADE;


--
-- Name: iscrizioni iscrizioni_studente_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.iscrizioni
    ADD CONSTRAINT iscrizioni_studente_fkey FOREIGN KEY (studente) REFERENCES unimi_it.studente(matricola) ON DELETE CASCADE;


--
-- Name: propedeuticità propedeuticità_insegnamento_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it."propedeuticità"
    ADD CONSTRAINT "propedeuticità_insegnamento_fkey" FOREIGN KEY (insegnamento) REFERENCES unimi_it.insegnamento(id);


--
-- Name: propedeuticità propedeuticità_propedeutico_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it."propedeuticità"
    ADD CONSTRAINT "propedeuticità_propedeutico_fkey" FOREIGN KEY (propedeutico) REFERENCES unimi_it.insegnamento(id);


--
-- Name: storico_carriera storico_carriera_esame_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.storico_carriera
    ADD CONSTRAINT storico_carriera_esame_fkey FOREIGN KEY (esame) REFERENCES unimi_it.esami(id);


--
-- Name: storico_carriera storico_carriera_studente_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.storico_carriera
    ADD CONSTRAINT storico_carriera_studente_fkey FOREIGN KEY (studente) REFERENCES unimi_it.storico_studente(matricola);


--
-- Name: storico_studente storico_studente_corso_frequentato_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.storico_studente
    ADD CONSTRAINT storico_studente_corso_frequentato_fkey FOREIGN KEY (corso_frequentato) REFERENCES unimi_it.corso(id);


--
-- Name: studente studente_corso_frequentato_fkey; Type: FK CONSTRAINT; Schema: unimi_it; 
--

ALTER TABLE ONLY unimi_it.studente
    ADD CONSTRAINT studente_corso_frequentato_fkey FOREIGN KEY (corso_frequentato) REFERENCES unimi_it.corso(id);


--
-- PostgreSQL database dump complete
--

