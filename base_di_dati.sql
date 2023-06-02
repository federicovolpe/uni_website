CREATE NEW DATABASE unimio;
CREATE SCHEMA unimi.it;

CREATE TABLE segreteria(
    email VARCHAR(100) PRIMARY KEY,
    passwrd VARCHAR(20) NOT NULL,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL
);

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
    corso_frequentato CHAR(100) REFERENCES corso(nome_corso) NOT NULL;
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
INSERT INTO corso (id, nome_corso, laurea) 
    VALUES 
    (9175738010, 'Sicurezza Informatica', 'magistrale');
    (2124169738, 'Chimica', 'triennale');
    (3759187480, 'Fisica', 'triennale');
    (7028895757, 'Informatica', 'triennale');
    (9202226989, 'Informatica Musicale', 'triennale');



CREATE TABLE insegnamento(
    id CHAR(10) PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL,
    descrizione TEXT,
    anno CHAR(4),
    corso VARCHAR(100) REFERENCES corso(nome_corso)
);

--popolazione tabella insegnamento 25 insegnamenti
insert into insegnamento (id, nome, descrizione, anno, corso) 
VALUES 
    (9914411111, 'Affidabilità dei sistemi', null, 2, 'Sicurezza Informatica');
    (1973869985, 'Artificial intelligence', null, 1, 'Sicurezza Informatica');
    (5210097035, 'Componenti di biometria', null, 2, 'Sicurezza Informatica');
    (3204759382, 'Crittografia', null, 1, 'Sicurezza Informatica');
    (2995667581, 'Information management', null, 2, 'Sicurezza Informatica');
    (3526956576, 'Fisica generale', null, 2, 'Chimica');
    (5837527637, 'Chimica generale', null, 2, 'Chimica');
    (1193746368, 'Istituzioni di matematica', null, 1, 'Chimica');
    (7393377009, 'Chimica analitica', null, 2, 'Chimica');
    (2852290568, 'Chimica organica', null, 1, 'Chimica');
    (6967310076, 'Chimica 1', null, 2, 'Fisica');
    (8650908123, 'Elettronica 1', null, 1, 'Fisica');
    (4864051855, 'Fisica quantistica', null, 3, 'Fisica');
    (4789640943, 'Geometria', null, 1, 'Fisica');
    (1402416704, 'Astrofisica', null, 2, 'Fisica');
    (5193752943, 'Logica', null, 3, 'Informatica');
    (7477422085, 'Sistemi embedded', null, 3, 'Informatica');
    (2485318062, 'Basi di dati', null, 3, 'Informatica');
    (6405967915, 'Programmazione 1', null, 1, 'Informatica');
    (9428798504, 'Programmazione 2', null, 2, 'Informatica');
    (7037905580, 'Editoria digitale', null, 2, 'Informatica Musicale');
    (6684943179, 'Crittografia sonora', null, 3, 'Informatica Musicale');
    (1655851251, 'Acustica', null, 2, 'Informatica Musicale');
    (1647855372, 'Algoritmi e strutture dati', null, 2, 'Informatica Musicale');
    (8610762518, 'Informazione multimediale', null, 1, 'Informatica Musicale');
    (4104514266, 'Informatica del suono', null, 3, 'Informatica Musicale');

CREATE TABLE esami(
    id CHAR(6) PRIMARY KEY,
    insegnamento CHAR(10) REFERENCES insegnamento(id),
    docente CHAR(6) REFERENCES docente(id),
    data CHAR(6)
);

--riempimento tabella esami
INSERT INTO esami (id, insegnamento, docente, data)
VALUES
  ('100001', '9914411111', '314434', '010623'),   -- Insegnamento '9914411111' by Docente '314434' on 01/06/23
  ('100002', '1973869985', '619231', '030623'),   -- Insegnamento '1973869985' by Docente '619231' on 03/06/23
  ('100003', '5210097035', '945514', '050623'),   -- Insegnamento '5210097035' by Docente '945514' on 05/06/23
  ('100004', '3204759382', '719958', '070623'),   -- Insegnamento '3204759382' by Docente '719958' on 07/06/23
  ('100005', '2995667581', '407789', '090623'),   -- Insegnamento '2995667581' by Docente '407789' on 09/06/23
  ('100006', '3526956576', '991385', '110623'),   -- Insegnamento '3526956576' by Docente '991385' on 11/06/23
  ('100007', '5837527637', '151049', '130623'),   -- Insegnamento '5837527637' by Docente '151049' on 13/06/23
  ('100008', '1193746368', '833919', '150623'),   -- Insegnamento '1193746368' by Docente '833919' on 15/06/23
  ('100009', '7393377009', '427844', '170623'),   -- Insegnamento '7393377009' by Docente '427844' on 17/06/23
  ('100010', '2852290568', '554198', '190623'),   -- Insegnamento '2852290568' by Docente '554198' on 19/06/23
  ('100011', '6967310076', '833919', '210623'),   -- Insegnamento '6967310076' by Docente '833919' on 21/06/23
  ('100012', '8650908123', '427844', '230623');   -- Insegnamento '8650908123' by Docente '427844' on 23/06/23



CREATE TABLE propedeuticità(
    insegnamento_1 CHAR(10) REFERENCES insegnamento(id),
    insegnamento_2 CHAR(10) REFERENCES insegnamento(id)
);


--tabella che assegna ad ogni insegnamento un docente responsabile
--  possono esserci anche più responsabili per ogni corso
CREATE TABLE responsabile_insegnamento(
    docente CHAR(6) REFERENCES docente(id),
    insegnamento CHAR(10) REFERENCES insegnamento(id)
);

--popolazione tabella responsabile_insegnamento
INSERT INTO responsabile_insegnamento (docente, insegnamento)
    VALUES
    ('314434', '9914411111'),
    ('619231', '1973869985'),
    ('945514', '5210097035'),
    ('719958', '3204759382'),
    ('407789', '2995667581'),
    ('833919', '3526956576'),  
    ('427844', '5837527637'),  
    ('554198', '1193746368'),  
    ('833919', '7393377009'),  
    ('427844', '2852290568'),  
    ('554198', '6967310076'),  
    ('833919', '8650908123'),  
    ('427844', '4864051855'),  
    ('554198', '4789640943'),  
    ('833919', '1402416704'),  
    ('427844', '5193752943'),  
    ('554198', '7477422085'),  
    ('833919', '2485318062'),  
    ('427844', '6405967915'),  
    ('554198', '9428798504'),  
    ('833919', '7037905580'),  
    ('427844', '6684943179'),  
    ('554198', '1655851251'),  
    ('833919', '1647855372'),  
    ('427844', '8610762518'),  
    ('554198', '4104514266');

CREATE TABLE responsabile_corso(
    docente CHAR(6) REFERENCES docente(id),
    corso CHAR(10) REFERENCES corso(id)
);

--popolazione della tabella responsabile_corso
INSERT INTO responsabile_corso (docente, corso)
VALUES
  ('314434', '9175738010'),
  ('619231', '2124169738'),
  ('945514', '3759187480'),
  ('719958', '7028895757'), 
  ('407789', '9202226989');

CREATE TABLE iscrizioni(
    studente CHAR(6) REFERENCES studente(matricola),
    esame CHAR(6) REFERENCES esami(id)
);



CREATE TABLE esiti(
        studente CHAR(6) REFERENCES studente(matricola),
        esame CHAR(6) REFERENCES esami(id),
        esito NUMERIC NOT NULL
    );

--tabella che per ogni studente contiene gli esami che lui ha prenotato
CREATE TABLE esami_prenotati(
    studente CHAR(6) REFERENCES studente, 
    esame CHAR(6) REFERENCES esami,
    PRIMARY KEY (studente, esame)
)
--              FUNZIONI

-- prima di inserire un esame devo controllare che l'insegnante specificato risulti responsabile di quell'insegnamento
CREATE OR REPLACE FUNCTION esame_insert()
    RETURNS TRIGGER

    AS$$
        BEGIN
            --se il docente non è responsabile di quel corso allora 
            IF new.docente not in(-- se
            
            )RAISE NOTICE 'il professore non risulta responsabile di questo corso';
        END
    $$ LANGUAGE 'plpgsql'

CREATE OR REPLACE TRIGGER esame_insert_trigger 
    AFTER INSERT ON esame 
    FOR EACH ROW 
    EXECUTE FUNCTION esame_insert();