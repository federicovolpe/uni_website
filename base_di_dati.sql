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

CREATE TABLE docente(
    id CHAR(6) PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    passwrd VARCHAR(20) NOT NULL,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
    -- controllo che la email finisca per @docenti.unimi.it
    CONSTRAINT email CHECK (email LIKE '%@docenti.unimi.it')
);

CREATE TABLE corso(
    id CHAR(10) PRIMARY KEY ON DELETE CASCADE,
    nome_corso VARCHAR(100) NOT NULL UNIQUE,
    laurea VARCHAR(10), 
    descrizione TEXT,
    CONSTRAINT laurea CHECK (laurea = 'triennale' OR laurea = 'magistrale')
);


CREATE TABLE insegnamento(
    id CHAR(10) PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL,
    descrizione TEXT,
    anno CHAR(4) NOT NULL,
    corso CHAR(10) REFERENCES corso(id) NOT NULL
);
CREATE TABLE esami(
    id CHAR(6) PRIMARY KEY,
    insegnamento CHAR(10) REFERENCES insegnamento(id),
    docente CHAR(6) REFERENCES docente(id),
    data DATE
);


CREATE TABLE propedeuticità(
    insegnamento CHAR(10) REFERENCES insegnamento(id),
    propedeutico CHAR(10) REFERENCES insegnamento(id)
);
insert into propedeuticità (insegnamento, propedeutico) 
values('9428798504','6405967915'); 



CREATE TABLE responsabile_corso(
    docente CHAR(6) REFERENCES docente(id) ON DELETE CASCADE,
    corso CHAR(10) REFERENCES corso(id) ON DELETE CASCADE
);


CREATE TABLE esiti(
    studente CHAR(6) REFERENCES studente(matricola),
    esame CHAR(6) REFERENCES esami(id),
    esito NUMERIC NOT NULL,
    PRIMARY KEY (studente, esame),
    CHECK (esito >= 0 AND esito <= 30)
);

--tabella che per ogni studente contiene gli esami che lui ha prenotato

CREATE TABLE iscrizioni(
    studente CHAR(6) REFERENCES studente(matricola) ON DELETE CASCADE,
    esame CHAR(6) REFERENCES esami(id) ON DELETE SET NULL,
    PRIMARY KEY (studente, esame)
);

--tabella che contiene lo storico dei dati degli studenti che sono stati cancellati
CREATE TABLE storico_studente(
    matricola CHAR(6) PRIMARY KEY,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
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

-- creare un trigger per l'inserimento di un nuovo esame da parte di un professore
-- il trigger provvederà alla creazione di un codice id unico

CREATE OR REPLACE FUNCTION generate_esami_id()
RETURNS TRIGGER AS $$
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
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_insert_esami
BEFORE INSERT ON esami
FOR EACH ROW
EXECUTE FUNCTION generate_esami_id();

-- trigger che prima di una iscrizione di uno studente ad un esame controlli 
--che siano già stati passati tutti gli esami propedeutici

CREATE OR REPLACE FUNCTION controllo_propedeutici()
    RETURNS TRIGGER
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
    $$ LANGUAGE plpgsql;


CREATE OR REPLACE TRIGGER controllo_propedeutici_trigger
    BEFORE INSERT ON iscrizioni
    FOR EACH ROW
    EXECUTE FUNCTION controllo_propedeutici();

-- trigger per la cancellazione di un corso, quando viene cancellato un corso vengono anche rimossi tutte le referenze nella
-- tablella responsabile_corso

CREATE OR REPLACE FUNCTION elimina_responsabilità_corso()
    RETURNS TRIGGER 
    AS $$
        BEGIN
            DELETE FROM responsabile_corso
            WHERE corso = OLD.id;
            RETURN OLD;
        END;
    $$
    LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER elimina_responsabilità_corso_trigger
    BEFORE DELETE ON corso
    FOR EACH ROW
    EXECUTE FUNCTION elimina_responsabilità_corso();

-- trigger simmetrico al precedente ma attivato quando viene cancellato un docente

CREATE OR REPLACE FUNCTION elimina_responsabilità_docente()
    RETURNS TRIGGER 
    AS $$
        BEGIN
            DELETE FROM responsabile_corso
            WHERE docente = OLD.id;
            RETURN OLD;
        END;
    $$
    LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER elimina_responsabilità_docente_trigger
    BEFORE DELETE ON docente
    FOR EACH ROW
    EXECUTE FUNCTION elimina_responsabilità_docente();