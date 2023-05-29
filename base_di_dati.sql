CREATE NEW DATABASE unimio;
CREATE SCHEMA unimi.it;

CREATE TABLE segreteria(
    email VARCHAR(100) PRIMARY KEY,
    passwrd VARCHAR(20) NOT NULL,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
)

INSERT INTO segreteria (email, passwrd, nome, cognome)
VALUES
  ('alice@example.com', 'password1', 'Alice', 'Smith'),
  ('bob@example.com', 'password2', 'Bob', 'Johnson'),
  ('charlie@example.com', 'password3', 'Charlie', 'Brown'),
  ('diana@example.com', 'password4', 'Diana', 'Wilson'),
  ('ethan@example.com', 'password5', 'Ethan', 'Davis');
  

CREATE TABLE studente(
    matricola CHAR(6) PRIMARY KEY,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    passwrd VARCHAR(20) NOT NULL,
    corso_frequentato CHAR(100) REFERENCES corso(nome_corso)
);

-- popolazione della tabella studente
INSERT INTO studente (matricola, nome, cognome, email, passwrd, corso_frequentato)
VALUES
  (986899, 'Ancell', 'Janssens', 'ancell@example.com', 'password1', 'Sicurezza Informatica'),
  (103246, 'Clara', 'Poyner', 'clara@example.com', 'password2', 'Sicurezza Informatica'),
  (148008, 'Karyn', 'Claybourn', 'karyn@example.com', 'password3', 'Chimica'),
  (511415, 'Harmony', 'MacClenan', 'harmony@example.com', 'password4', 'Chimica'),
  (903187, 'Raine', 'Di Carli', 'raine@example.com', 'password5', 'Chimica'),
  (722630, 'Anissa', 'Guitel', 'anissa@example.com', 'password6', 'Fisica'),
  (358096, 'Vernen', 'Moss', 'vernen@example.com', 'password7', 'Fisica'),
  (208078, 'Annabal', 'Pamment', 'annabal@example.com', 'password8', 'Fisica'),
  (147692, 'Brigit', 'Jain', 'brigit@example.com', 'password9', 'Informatica'),
  (905716, 'Dena', 'Barringer', 'dena@example.com', 'password10', 'Informatica'),
  (657874, 'Willetta', 'Abrahami', 'willetta@example.com', 'password11', 'Informatica'),
  (571848, 'Gardner', 'Strand', 'gardner@example.com', 'password12', 'Informatica'),
  (645198, 'Worden', 'Gilhouley', 'worden@example.com', 'password13', 'Informatica Musicale'),
  (342631, 'Bibi', 'Huskinson', 'bibi@example.com', 'password14', 'Informatica Musicale'),
  (596144, 'Madlin', 'Findley', 'madlin@example.com', 'password15', 'Informatica Musicale');


CREATE TABLE docente(
    id CHAR(6) PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    passwrd VARCHAR(20) NOT NULL,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL
);

--popolazione della tabella docente
INSERT INTO docente (id, nome, cognome, email, passwrd)
VALUES
  (314434, 'Bernardine', 'Harniman', 'bernardine@example.com', 'password1'),
  (619231, 'Husein', 'Dugue', 'husein@example.com', 'password2'),
  (945514, 'Leigh', 'Bakster', 'leigh@example.com', 'password3'),
  (719958, 'Shaughn', 'Fouracres', 'shaughn@example.com', 'password4'),
  (407789, 'Lina', 'Somerlie', 'lina@example.com', 'password5'),
  (991385, 'Guthrie', 'Rove', 'guthrie@example.com', 'password6'),
  (151049, 'Galven', 'Scothorne', 'galven@example.com', 'password7'),
  (833919, 'Dalia', 'Izaac', 'dalia@example.com', 'password8'),
  (427844, 'Elwyn', 'Renneke', 'elwyn@example.com', 'password9'),
  (554198, 'Buddie', 'MacCart', 'buddie@example.com', 'password10');


CREATE TABLE corso(
    id CHAR(10) PRIMARY KEY,
    nome_corso VARCHAR(100) NOT NULL UNIQUE,
    laurea VARCHAR(10), 
    CONSTRAINT laurea CHECK (laurea = 'triennale' OR laurea = 'magistrale')
);

--popolazione della tabella corso
insert into corso (id, nome_corso, laurea) values (9175738010, 'Sicurezza Informatica', 'magistrale');
insert into corso (id, nome_corso, laurea) values (2124169738, 'Chimica', 'triennale');
insert into corso (id, nome_corso, laurea) values (3759187480, 'Fisica', 'triennale');
insert into corso (id, nome_corso, laurea) values (7028895757, 'Informatica', 'triennale');
insert into corso (id, nome_corso, laurea) values (9202226989, 'Informatica Musicale', 'triennale');



CREATE TABLE insegnamento(
    id CHAR(10) PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL,
    descrizione TEXT,
    anno CHAR(4),
    corso VARCHAR(100) REFERENCES corso(nome_corso)
);

--popolazione tabella insegnamento 25 insegnamenti
insert into insegnamento (id, nome, descrizione, anno, corso) values (9914411111, 'Affidabilità dei sistemi', null, 2, 'Sicurezza Informatica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (1973869985, 'Artificial intelligence', null, 1, 'Sicurezza Informatica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (5210097035, 'Componenti di biometria', null, 2, 'Sicurezza Informatica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (3204759382, 'Crittografia', null, 1, 'Sicurezza Informatica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (2995667581, 'Information management', null, 2, 'Sicurezza Informatica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (3526956576, 'Fisica generale', null, 2, 'Chimica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (5837527637, 'Chimica generale', null, 2, 'Chimica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (1193746368, 'Istituzioni di matematica', null, 1, 'Chimica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (7393377009, 'Chimica analitica', null, 2, 'Chimica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (2852290568, 'Chimica organica', null, 1, 'Chimica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (6967310076, 'Chimica 1', null, 2, 'Fisica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (8650908123, 'Elettronica 1', null, 1, 'Fisica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (4864051855, 'Fisica quantistica', null, 3, 'Fisica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (4789640943, 'Geometria', null, 1, 'Fisica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (1402416704, 'Astrofisica', null, 2, 'Fisica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (5193752943, 'Logica', null, 3, 'Informatica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (7477422085, 'Sistemi embedded', null, 3, 'Informatica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (2485318062, 'Basi di dati', null, 3, 'Informatica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (6405967915, 'Programmazione 1', null, 1, 'Informatica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (9428798504, 'Programmazione 2', null, 2, 'Informatica');
insert into insegnamento (id, nome, descrizione, anno, corso) values (7037905580, 'Editoria digitale', null, 2, 'Informatica Musicale');
insert into insegnamento (id, nome, descrizione, anno, corso) values (6684943179, 'Crittografia sonora', null, 3, 'Informatica Musicale');
insert into insegnamento (id, nome, descrizione, anno, corso) values (1655851251, 'Acustica', null, 2, 'Informatica Musicale');
insert into insegnamento (id, nome, descrizione, anno, corso) values (1647855372, 'Algoritmi e strutture dati', null, 2, 'Informatica Musicale');
insert into insegnamento (id, nome, descrizione, anno, corso) values (8610762518, 'Informazione multimediale', null, 1, 'Informatica Musicale');
insert into insegnamento (id, nome, descrizione, anno, corso) values (4104514266, 'Informatica del suono', null, 3, 'Informatica Musicale');

CREATE TABLE esami(
    id CHAR(6) PRIMARY KEY,
    insegnamento VARCHAR(100) REFERENCES insegnamento(id),
    docente CHAR(6) REFERENCES docente(id)
);

CREATE TABLE propedeuticità(
    insegnamento_1 CHAR(10) REFERENCES insegnamento(id),
    insegnamento_2 CHAR(10) REFERENCES insegnamento(id)
);

CREATE TABLE responsabile_corso(
    docente CHAR(6) REFERENCES docente(id),
    insegnamento CHAR(10) REFERENCES insegnamento(id)
);

CREATE TABLE iscrizioni(
    studente CHAR(6) REFERENCES studente(matricola),
    esame CHAR(6) REFERENCES esami(id)
);

    CREATE TABLE esiti(
        studente CHAR(6) REFERENCES studente(matricola),
        esame CHAR(6) REFERENCES esami(id),
        esito NUMERIC NOT NULL
    );

--              FUNZIONI