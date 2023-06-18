per quanto riguarda il database denominato unimio ho deciso di implementare un solo schema(denomiato unimi_it) contenente tutte le tabelle necessarie
quindi il SEARCH_PATH deve necessariamente essere settato su unimi_it

lo schema entità-relazione è stato implementato nel seguente modo:
++immagine++

successivamente alla stesura dello schema er sono state fatte delle migliorie per diminuire il numero di tabelle necessarie il più possibile
ho deciso cosi di incorporare le relazioni che collegano due entità con un ramo [1-1] nella stessa tabella a cui è collegato sotto forma di attributo che referenzia all'altra entità
risparmiando così una tabella per la relazione.

la entità segreteria pur non avendo relazioni che la collegano alle altre entità ha la possibilità di definire nuovi utenti e nuovi corsi
(questo è stato implementato con le frecce nello schema).

lo schema logico viene riportato di seguito, segue la stessa semantica di quello er

il database
il database è stato popolato per renderlo il più realistico possibile e in modo tale che possano essere sperimentate 
quante più funzionalità possibili della applicazione web

di seguito vengono riportati i trigger che sono stati implementati:



-- prima di inserire un esame devo controllare che l'insegnante specificato risulti responsabile di quell'insegnamento

 responsabile_insegnamento_trigger

-- trigger che evita che un professore diventi responsabile di piu di 3 insegnamenti

insegnamenti_responsabile_trigger

--    - [ ] Trigger che vieta la modifica/eliminazione di un esame se il professore non è responsabile

update_esame_trigger

-- trigger che prima dell'inserzione di un esito per un  esame controlli
--  che lo studente risulti effettivamente iscritto per quell'esame

verifica_iscrizione_trigger

-- trigger che prima della cancellazione di uno studente sposta i suoi dati sul database

cancellazione_studente_trigger

-- trigger per il salvataggio dei dati riguardanti un esito cancellato(dato dalla cancellazione di uno studente)

salvataggio_esiti_trigger

-- creare un trigger per l'inserimento di un nuovo esame da parte di un professore
-- il trigger provvederà alla creazione di un codice id unico

before_insert_esami

-- trigger che prima di una iscrizione di uno studente ad un esame controlli 
--che siano già stati passati tutti gli esami propedeutici

controllo_propedeutici_trigger

-- trigger per la cancellazione di un corso, quando viene cancellato un corso vengono anche rimossi tutte le referenze nella
-- tablella responsabile_corso

elimina_responsabilità_corso_trigger

-- trigger simmetrico al precedente ma attivato quando viene cancellato un docente
elimina_responsabilità_docente_trigger