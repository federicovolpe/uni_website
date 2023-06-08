CREATE NEW DATABASE unimio;
CREATE SCHEMA unimi.it;

CREATE TABLE segreteria(
    id CHAR(6) PRIMARY KEY,
    email VARCHAR(100) UNIQUE,
    passwrd VARCHAR(20) NOT NULL,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,

    -- CONTROLLO CHE LA EMAIL FINISCA PER @segreteria.unimi.it
    CONSTRAINT email CHECK (email LIKE '%@segreteria.unimi.it')
);

INSERT INTO segreteria (id,email, passwrd, nome, cognome)
VALUES
  ('000001','alice@segreteria.unimi.it', 'password1', 'Alice', 'Smith'),
  ('000002','bob@segreteria.unimi.it', 'password2', 'Bob', 'Johnson'),
  ('000003','charlie@segreteria.unimi.it', 'password3', 'Charlie', 'Brown'),
  ('000004','diana@segreteria.unimi.it', 'password4', 'Diana', 'Wilson'),
  ('000005','ethan@segreteria.unimi.it', 'password5', 'Ethan', 'Davis');
  

CREATE TABLE studente(
    matricola CHAR(6) PRIMARY KEY,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    passwrd VARCHAR(20) NOT NULL,
    corso_frequentato CHAR(100) REFERENCES corso(id) NOT NULL,
    -- controllo che la email finisca per @studenti.unimi.it
    CONSTRAINT email CHECK (email LIKE '%@studenti.unimi.it')
);

-- popolazione della tabella studente
INSERT INTO studente (matricola, nome, cognome, email, passwrd, corso_frequentato)
VALUES
  (986899, 'Ancell',    'Janssens', 'ancell@studenti.unimi.it', 'password1',  9175738010),
  (103246, 'Clara',     'Poyner', 'clara@studenti.unimi.it', 'password2',     9175738010),
  (148008, 'Karyn',     'Claybourn', 'karyn@studenti.unimi.it', 'password3',  2124169738),
  (511415, 'Harmony',   'MacClenan', 'harmony@studenti.unimi.it', 'password4',2124169738),
  (903187, 'Raine',     'Di Carli', 'raine@studenti.unimi.it', 'password5',   2124169738),
  (722630, 'Anissa',    'Guitel', 'anissa@studenti.unimi.it', 'password6',    3759187480),
  (358096, 'Vernen',    'Moss', 'vernen@studenti.unimi.it', 'password7',      3759187480),
  (208078, 'Annabal',   'Pamment', 'annabal@studenti.unimi.it', 'password8',  3759187480),
  (147692, 'Brigit',    'Jain', 'brigit@studenti.unimi.it', 'password9',      7028895757),
  (905716, 'Dena',      'Barringer', 'dena@studenti.unimi.it', 'password10',  7028895757),
  (657874, 'Willetta',  'Abrahami', 'willetta@studenti.unimi.it', 'password11',7028895757),
  (571848, 'Gardner',   'Strand', 'gardner@studenti.unimi.it', 'password12',  7028895757),
  (645198, 'Worden',    'Gilhouley', 'worden@studenti.unimi.it', 'password13',9202226989),
  (342631, 'Bibi',      'Huskinson', 'bibi@studenti.unimi.it', 'password14',  9202226989),
  (596144, 'Madlin',    'Findley', 'madlin@studenti.unimi.it', 'password15',  9202226989),
  (966031, 'Federico',  'Volpe', 'federico.volpe@studenti.unimi.it','abc',    7028895757),
  (986892  , 'prova',  'prova', 'prova@studenti.unimi.it'  ,'password1' ,9175738010  );


CREATE TABLE docente(
    id CHAR(6) PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    passwrd VARCHAR(20) NOT NULL,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
    -- controllo che la email finisca per @docenti.unimi.it
    CONSTRAINT email CHECK (email LIKE '%@docenti.unimi.it')
);

--popolazione della tabella docente
INSERT INTO docente (id, nome, cognome, email, passwrd)
VALUES
  (314434, 'Bernardine', 'Harniman',    'bernardine@docenti.unimi.it', 'password1'),
  (619231, 'Husein',     'Dugue',       'husein@docenti.unimi.it',     'password2'),
  (945514, 'Leigh',      'Bakster',     'leigh@docenti.unimi.it',      'password3'),
  (719958, 'Shaughn',    'Fouracres',   'shaughn@docenti.unimi.it',    'password4'),
  (407789, 'Lina',       'Somerlie',    'lina@docenti.unimi.it',       'password5'),
  (991385, 'Guthrie',    'Rove',        'guthrie@docenti.unimi.it',    'password6'),
  (151049, 'Galven',     'Scothorne',   'galven@docenti.unimi.it',     'password7'),
  (833919, 'Dalia',      'Izaac',       'dalia@docenti.unimi.it',      'password8'),
  (427844, 'Elwyn',      'Renneke',     'elwyn@docenti.unimi.it',      'password9'),
  (554198, 'Buddie',     'MacCart',     'buddie@docenti.unimi.ithg b', 'password10');


CREATE TABLE corso(
    id CHAR(10) PRIMARY KEY,
    nome_corso VARCHAR(100) NOT NULL UNIQUE,
    laurea VARCHAR(10), 
    CONSTRAINT laurea CHECK (laurea = 'triennale' OR laurea = 'magistrale')
);

--popolazione della tabella corso
INSERT INTO corso (id, nome_corso, laurea) 
    VALUES 
    (9175738010, 'Sicurezza Informatica',   'magistrale'),
    (2124169738, 'Chimica',                 'triennale' ),
    (3759187480, 'Fisica',                  'triennale' ),
    (7028895757, 'Informatica',             'triennale' ),
    (9202226989, 'Informatica Musicale',    'triennale' );



CREATE TABLE insegnamento(
    id CHAR(10) PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL,
    descrizione TEXT,
    anno CHAR(4) NOT NULL,
    corso CHAR(10) REFERENCES corso(id) NOT NULL
);

--popolazione tabella insegnamento 25 insegnamenti
insert into insegnamento (id, nome, descrizione, anno, corso) 
VALUES 
    (9914411111, 'Affidabilità dei sistemi', null, 2,   9175738010),
    (1973869985, 'Artificial intelligence', null, 1,    9175738010),
    (5210097035, 'Componenti di biometria', null, 2,    9175738010),
    (3204759382, 'Crittografia', null, 1,               9175738010),
    (2995667581, 'Information management', null, 2,     9175738010),
    (3526956576, 'Fisica generale', null, 2,            2124169738),
    (5837527637, 'Chimica generale', null, 2,           2124169738),
    (1193746368, 'Istituzioni di matematica', null, 1,  2124169738),
    (7393377009, 'Chimica analitica', null, 2,          2124169738),
    (2852290568, 'Chimica organica', null, 1,           2124169738),
    (6967310076, 'Chimica 1', null, 2,                  3759187480),
    (8650908123, 'Elettronica 1', null, 1,              3759187480),
    (4864051855, 'Fisica quantistica', null, 3,         3759187480),
    (4789640943, 'Geometria', null, 1,                  3759187480),
    (1402416704, 'Astrofisica', null, 2,                3759187480),
    (5193752943, 'Logica', null, 3,                     7028895757),
    (7477422085, 'Sistemi embedded', null, 3,           7028895757),
    (2485318062, 'Basi di dati', null, 3,               7028895757),
    (6405967915, 'Programmazione 1', null, 1,           7028895757),
    (9428798504, 'Programmazione 2', null, 2,           7028895757),
    (7037905580, 'Editoria digitale', null, 2,          9202226989),
    (6684943179, 'Crittografia sonora', null, 3,        9202226989),
    (1655851251, 'Acustica', null, 2,                   9202226989),
    (1647855372, 'Algoritmi e strutture dati', null, 2, 9202226989),
    (8610762518, 'Informazione multimediale', null, 1,  9202226989),
    (4104514266, 'Informatica del suono', null, 3,      9202226989);

CREATE TABLE esami(
    id CHAR(6) PRIMARY KEY,
    insegnamento CHAR(10) REFERENCES insegnamento(id),
    docente CHAR(6) REFERENCES docente(id),
    data CHAR(6)
);

--riempimento tabella esami
INSERT INTO esami (id, insegnamento, docente, data)
VALUES
  ('100001', '9914411111', '314434', '010623'), 
  ('100002', '1973869985', '619231', '030623'), 
  ('100003', '5210097035', '945514', '050623'), 
  ('100004', '3204759382', '719958', '070623'), 
  ('100005', '2995667581', '407789', '090623'), 
  ('100006', '3526956576', '833919', '110623'), 
  ('100007', '5837527637', '427844', '130623'), 
  ('100008', '1647855372', '833919', '150623'), 
  ('100009', '8610762518', '427844', '170623'), 
  ('100010', '7477422085', '554198', '190623'), 
  ('100011', '3526956576', '833919', '210623'), 
  ('100012', '5837527637', '427844', '230623'),
  ('100013', '6967310076', '554198', '230522'),
  ('100014', '6967310076', '554198', '220522'),
  ('100015', '1193746368', '554198', '231222'),
  ('000001', '7477422085', '554198', '160623'), --esame di sistemi embedded
  ('000002', '6405967915', '427844', '150623'),  --esame di programmazione1
  ('000003', '9428798504', '554198', '150623'), --esame di programmazione2
  ('000004', '5193752943', '427844', '049722'); --esame di logica



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





CREATE TABLE esiti(
    studente CHAR(6) REFERENCES studente(matricola),
    esame CHAR(6) REFERENCES esami(id),
    esito NUMERIC NOT NULL,
    PRIMARY KEY (studente, esame),
    CHECK (esito >= 0 AND esito <= 30)
);
--popolazione della tabella

INSERT INTO esiti (studente, esame, esito)
    VALUES
    ('966031', '000004', 18), --esame di logica di federico
    ('966031', '000003', 27), --esame di programmazione2 di federico
    ('966031', '000002', 24); --esame di programmazione1 di federico

--tabella che per ogni studente contiene gli esami che lui ha prenotato

CREATE TABLE iscrizioni(
    studente CHAR(6) REFERENCES studente(matricola) ON DELETE CASCADE,
    esame CHAR(6) REFERENCES esami(id),
    PRIMARY KEY (studente, esame)
);

INSERT INTO iscrizioni(studente, esame)
    VALUES
        ('986899' , '100001'),
        ('986899'  ,  '100004'),
        ('966031'   , '100010'),
        ('966031'   , '000001'),
        ('966031'   , '000002'),
        ('966031'   , '000003'),
        ('966031'   , '000004'),
        ('986892'   , '000002');


--tabella che contiene lo storico dei dati degli studenti che sono stati cancellati
CREATE TABLE storico_studente(
    matricola CHAR(6) PRIMARY KEY,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    passwrd VARCHAR(20) NOT NULL,
    corso_frequentato CHAR(100) REFERENCES corso(id) NOT NULL,
    -- controllo che la email finisca per @studenti.unimi.it
    CONSTRAINT email CHECK (email LIKE '%@studenti.unimi.it')
);

--tabella che contiene tutto lo storico dei voti degli studenti che sono stati cancellati
CREATE TABLE storico_carriera(
    studente CHAR(6) REFERENCES storico_studente(matricola),
    esame CHAR(6) REFERENCES esami(id),
    esito NUMERIC NOT NULL,
    PRIMARY KEY (studente, esame),
    CHECK (esito >= 0 AND esito <= 30)
);




















--              FUNZIONI




















-- prima di inserire un esame devo controllare che l'insegnante specificato risulti responsabile di quell'insegnamento

CREATE OR REPLACE FUNCTION check_responsabile_insegnamento()
  RETURNS TRIGGER AS $$
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
    $$ LANGUAGE plpgsql;

-- Create the trigger
CREATE TRIGGER responsabile_insegnamento_trigger
BEFORE INSERT ON esami
FOR EACH ROW
EXECUTE FUNCTION check_responsabile_insegnamento();

-- trigger che evita che un professore diventi responsabile di piu di 3 insegnamenti

CREATE OR REPLACE FUNCTION n_insegnamenti_responsabile()
    RETURNS TRIGGER AS $$
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
    $$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER insegnamenti_responsabile_trigger
    BEFORE INSERT ON responsabile_insegnamento
    FOR EACH ROW
    EXECUTE FUNCTION n_insegnamenti_responsabile();

--    - [ ] Trigger che vieta la modifica/eliminazione di un esame se il professore non è responsabile
CREATE OR REPLACE FUNCTION update_esame(professore)
    RETURNS TRIGGER 
    AS $$
        BEGIN
        END;
    $$ LANGUAGE plpgsql


CREATE OR REPLACE TRIGGER update_esame_trigger
    BEFORE UPDATE OR DELETE ON esame
    FOR EACH ROW
    EXECUTE FUNCTION update_esame(professore);

-- trigger che prima dell'inserzione di un esito per un  esame controlli
--  che lo studente risulti effettivamente iscritto per quell'esame

CREATE OR REPLACE FUNCTION verifica_iscrizione()
    RETURNS TRIGGER 
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
    $$ LANGUAGE plpgsql;


CREATE OR REPLACE TRIGGER verifica_iscrizione_trigger
    BEFORE INSERT ON esiti
    FOR EACH ROW
    EXECUTE FUNCTION verifica_iscrizione();


-- trigger che prima della cancellazione di uno studente sposta i suoi dati sul database

CREATE OR REPLACE FUNCTION cancellazione_studente()
    RETURNS TRIGGER
    AS $$
    BEGIN
        -- INSERZIONE dei valori della tabella studente nello storico
        INSERT INTO storico_studente (matricola, nome, cognome, email, passwrd, corso_frequentato)
        VALUES (OLD.matricola, OLD.nome, OLD.cognome, OLD.email, OLD.passwrd, OLD.corso_frequentato);

        RETURN OLD;
    END;
    $$ LANGUAGE plpgsql;


CREATE OR REPLACE TRIGGER cancellazione_studente_trigger
    BEFORE DELETE ON studente
    FOR EACH ROW
    EXECUTE FUNCTION cancellazione_studente();



-- trigger per il salvataggio dei dati riguardanti un esito cancellato(dato dalla cancellazione di uno studente)
CREATE OR REPLACE FUNCTION salvataggio_esiti()
    RETURNS TRIGGER
    AS $$
        BEGIN
            INSERT INTO storico_carriera (studente, esame, esito)
            VALUES (OLD.studente, OLD.esame, OLD.esito);
            RETURN OLD;
        END;
    $$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER salvataggio_esiti_trigger
    BEFORE DELETE ON esiti
    FOR EACH ROW
    EXECUTE FUNCTION salvataggio_esiti();