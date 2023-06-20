CREATE NEW DATABASE unimio;
CREATE SCHEMA unimi.it;

TABLE segreteria(
    id CHAR(6) PRIMARY KEY,
    email VARCHAR(100) UNIQUE,
    passwrd VARCHAR(20) NOT NULL,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,

    -- CONTROLLO CHE LA EMAIL FINISCA PER @segreteria.unimi.it
    CONSTRAINT email CHECK (email LIKE '%@segreteria.unimi.it')
);

TABLE studente(
    matricola CHAR(6) PRIMARY KEY,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    passwrd VARCHAR(20) NOT NULL,
    corso_frequentato CHAR(100) REFERENCES corso(id) ON DELETE SET NULL,
    -- controllo che la email finisca per @studenti.unimi.it
    CONSTRAINT email CHECK (email LIKE '%@studenti.unimi.it')
);

TABLE docente(
    id CHAR(6) PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    passwrd VARCHAR(20) NOT NULL,
    nome VARCHAR(20) NOT NULL,
    cognome VARCHAR(20) NOT NULL,
    -- controllo che la email finisca per @docenti.unimi.it
    CONSTRAINT email CHECK (email LIKE '%@docenti.unimi.it')
);

TABLE corso(
    id CHAR(10) PRIMARY KEY ON DELETE CASCADE,
    nome_corso VARCHAR(100) NOT NULL UNIQUE,
    laurea VARCHAR(10), 
    descrizione TEXT,
    responsabile character(6) REFERENCES docente(id) ON DELETE CASCADE,
    CONSTRAINT laurea CHECK (laurea = 'triennale' OR laurea = 'magistrale')
);


TABLE insegnamento(
    id CHAR(10) PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL,
    descrizione TEXT,
    anno CHAR(4) NOT NULL,
    corso CHAR(10) REFERENCES corso(id) ON DELETE SET NULL,
    responsabile character(6) REFERENCES unimi_it.docente(id) ON DELETE CASCADE
);

TABLE esami(
    id CHAR(6) PRIMARY KEY,
    insegnamento CHAR(10) REFERENCES insegnamento(id),
    docente CHAR(6) REFERENCES docente(id) ON DELETE CASCADE,
    data DATE
);


TABLE propedeuticità(
    insegnamento character(10) REFERENCES insegnamento(id) REFERENCES insegnamento(id) ON DELETE CASCADE,
    propedeutico character(10) REFERENCES insegnamento(id) REFERENCES insegnamento(id) ON DELETE CASCADE
);


TABLE esiti(
    studente CHAR(6) REFERENCES studente(matricola) ON DELETE CASCADE,
    esame CHAR(6) REFERENCES esami(id) ON DELETE CASCADE,
    esito NUMERIC NOT NULL,
    PRIMARY KEY (studente, esame),
    CHECK (esito >= 0 AND esito <= 30)
);

--tabella che per ogni studente contiene gli esami che lui ha prenotato

TABLE iscrizioni(
    studente CHAR(6) REFERENCES studente(matricola) ON DELETE CASCADE,
    esame CHAR(6) REFERENCES esami(id) ON DELETE SET NULL,
    PRIMARY KEY (studente, esame)
);

--tabella che contiene lo storico dei dati degli studenti che sono stati cancellati
TABLE storico_studente(
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
TABLE storico_carriera(
    studente CHAR(6) REFERENCES storico_studente(matricola),
    esame CHAR(6) REFERENCES esami(id),
    esito NUMERIC NOT NULL,
    PRIMARY KEY (studente, esame),
    CHECK (esito >= 0 AND esito <= 30)
);




--              FUNZIONI E TRIGGER






-- trigger che evita che un professore diventi responsabile di piu di 3 insegnamenti

FUNCTION n_insegnamenti_responsabile()
    RETURNS TRIGGER AS $$
    DECLARE 
        conta_docente INTEGER;
    BEGIN
        SELECT COUNT(*) INTO conta_docente
        FROM insegnamento
        WHERE responsabile = NEW.responsabile;

        IF (conta_docente >= 3) THEN 
            RAISE EXCEPTION 'Il docente non può essere responsabile di più di 3 insegnamenti';
        END IF;

        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;

 TRIGGER insegnamenti_responsabile_trigger
    BEFORE INSERT ON insegnamento
    FOR EACH ROW
    EXECUTE FUNCTION n_insegnamenti_responsabile();

-- trigger che prima dell'inserzione di un esito per un  esame controlli
--  che lo studente risulti effettivamente iscritto per quell'esame

 FUNCTION verifica_iscrizione()
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


 TRIGGER verifica_iscrizione_trigger
    BEFORE INSERT ON esiti
    FOR EACH ROW
    EXECUTE FUNCTION verifica_iscrizione();


-- trigger che prima della cancellazione di uno studente sposta i suoi dati sul database

 FUNCTION cancellazione_studente()
    RETURNS TRIGGER
    AS $$
    BEGIN
        -- INSERZIONE dei valori della tabella studente nello storico
        INSERT INTO storico_studente (matricola, nome, cognome, email, passwrd, corso_frequentato)
        VALUES (OLD.matricola, OLD.nome, OLD.cognome, OLD.email, OLD.passwrd, OLD.corso_frequentato);

        RETURN OLD;
    END;
    $$ LANGUAGE plpgsql;


 TRIGGER cancellazione_studente_trigger
    BEFORE DELETE ON studente
    FOR EACH ROW
    EXECUTE FUNCTION cancellazione_studente();



-- trigger per il salvataggio dei dati riguardanti un esito cancellato(dato dalla cancellazione di uno studente)
 FUNCTION salvataggio_esiti()
    RETURNS TRIGGER
    AS $$
        BEGIN
            INSERT INTO storico_carriera (studente, esame, esito)
            VALUES (OLD.studente, OLD.esame, OLD.esito);
            RETURN OLD;
        END;
    $$ LANGUAGE plpgsql;

 TRIGGER salvataggio_esiti_trigger
    BEFORE DELETE ON esiti
    FOR EACH ROW
    EXECUTE FUNCTION salvataggio_esiti();

-- creare un trigger per l'inserimento di un nuovo esame da parte di un professore
-- il trigger provvederà alla creazione di un codice id unico

 FUNCTION generate_esami_id()
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

 FUNCTION controllo_propedeutici()
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


 TRIGGER controllo_propedeutici_trigger
    BEFORE INSERT ON iscrizioni
    FOR EACH ROW
    EXECUTE FUNCTION controllo_propedeutici();

-- trigger che vieta l'inserzione di più di un esame per giorno se l'insegnamento relativo all'esame 
-- coincide con l'esame di un insegnamento dello stesso corso del primo

CREATE OR REPLACE FUNCTION esami_giornalieri()
    RETURNS TRIGGER
    AS $$
    DECLARE
        exam_count INTEGER; -- variabile per la conta 
        corso_id CHAR(10); -- per segnarmi l'id del corso di new
    BEGIN
        -- Fetch the corso ID associated with the insegnamento of the new exam
        SELECT I.corso
        INTO corso_id
        FROM insegnamento AS I
        WHERE I.id = NEW.insegnamento;

        SELECT COUNT(*)
        INTO exam_count
        FROM esami AS E
        JOIN insegnamento AS I ON I.id = E.insegnamento
        WHERE I.corso = corso_id AND E.data = NEW.data;

        -- se risulta già un esame in questa data
        IF exam_count >= 1 THEN
            RAISE EXCEPTION 'Ci sono già esami di questo corso programmati in questa data.';
        END IF;

        RETURN NEW;
    END;
    $$
    LANGUAGE plpgsql;


CREATE OR REPLACE TRIGGER esami_giornalieri
    BEFORE INSERT ON esami
    FOR EACH ROW
    EXECUTE FUNCTION esami_giornalieri(); 

