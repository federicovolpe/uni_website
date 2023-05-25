CREATE NEW DATABASE unimio;
CREATE SCHEMA unimi.it;

CREATE TABLE studente(
    matricola CHAR(6) PRIMARY KEY,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
    corso_frequentato CHAR(100) REFERENCES corso(nome_corso)
);

-- popolazione della tabella studente
insert into studente (matricola, nome, cognome, corso_frequentato) values (986899, 'Ancell', 'Janssens', 'Sicurezza Informatica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (103246, 'Clara', 'Poyner', 'Sicurezza Informatica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (148008, 'Karyn', 'Claybourn', 'Chimica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (511415, 'Harmony', 'MacClenan', 'Chimica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (903187, 'Raine', 'Di Carli', 'Chimica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (722630, 'Anissa', 'Guitel', 'Fisica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (358096, 'Vernen', 'Moss', 'Fisica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (208078, 'Annabal', 'Pamment', 'Fisica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (147692, 'Brigit', 'Jain', 'Informatica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (905716, 'Dena', 'Barringer', 'Informatica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (657874, 'Willetta', 'Abrahami', 'Informatica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (571848, 'Gardner', 'Strand', 'Informatica');
insert into studente (matricola, nome, cognome, corso_frequentato) values (645198, 'Worden', 'Gilhouley', 'Informatica Musicale');
insert into studente (matricola, nome, cognome, corso_frequentato) values (342631, 'Bibi', 'Huskinson', 'Informatica Musicale');
insert into studente (matricola, nome, cognome, corso_frequentato) values (596144, 'Madlin', 'Findley', 'Informatica Musicale');


CREATE TABLE docente(
    id CHAR(6) PRIMARY KEY,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL
);

--popolazione della tabella docente
insert into docente (id, nome, cognome) values (314434, 'Bernardine', 'Harniman');
insert into docente (id, nome, cognome) values (619231, 'Husein', 'Dugue');
insert into docente (id, nome, cognome) values (945514, 'Leigh', 'Bakster');
insert into docente (id, nome, cognome) values (719958, 'Shaughn', 'Fouracres');
insert into docente (id, nome, cognome) values (407789, 'Lina', 'Somerlie');
insert into docente (id, nome, cognome) values (991385, 'Guthrie', 'Rove');
insert into docente (id, nome, cognome) values (151049, 'Galven', 'Scothorne');
insert into docente (id, nome, cognome) values (833919, 'Dalia', 'Izaac');
insert into docente (id, nome, cognome) values (427844, 'Elwyn', 'Renneke');
insert into docente (id, nome, cognome) values (554198, 'Buddie', 'MacCart');


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



