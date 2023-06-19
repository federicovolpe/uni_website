# Progetto Database Unimi

Ho deciso di implementare un unico schema chiamato "unimi_it" per il database denominato "unimio". Questo schema conterrà tutte le tabelle necessarie per il progetto.

Lo schema entità-relazione è stato strutturato come mostrato nell'immagine seguente:

<div style="display: flex; justify-content: center;">
    <img src="../media/er_schema.png" alt="Image" style="width: 90%;border: 2px solid blue;">
</div>

Successivamente alla creazione dello schema ER, ho apportato alcune migliorie al fine di ridurre il numero di tabelle necessarie. In particolare, ho deciso di incorporare le relazioni [1-1] tra due entità nella stessa tabella, sotto forma di attributo che fa riferimento all'altra entità. Questo ha permesso di risparmiare una tabella per la relazione.

L'entità "segreteria", anche se non ha relazioni con le altre entità, ha la possibilità di definire nuovi utenti e nuovi corsi. Questa funzionalità è stata implementata utilizzando le frecce nello schema.

## Schema Logico


Il database è stato popolato con dati realistici al fine di consentire l'esplorazione delle varie funzionalità dell'applicazione web.

Di seguito vengono elencati i trigger che sono stati implementati:

- `responsabile_insegnamento_trigger`: Prima di inserire un esame, viene effettuato un controllo per verificare che l'insegnante specificato sia responsabile di quell'insegnamento.

- `insegnamenti_responsabile_trigger`: Questo trigger impedisce a un professore di diventare responsabile di più di tre insegnamenti.

- `update_esame_trigger`: Trigger che impedisce la modifica/eliminazione di un esame se il professore non è responsabile.

- `verifica_iscrizione_trigger`: Prima di inserire un esito per un esame, viene verificato che lo studente sia effettivamente iscritto a quell'esame.

- `cancellazione_studente_trigger`: Prima di cancellare uno studente, i suoi dati vengono spostati nel database.

- `salvataggio_esiti_trigger`: Trigger per il salvataggio dei dati riguardanti un esito cancellato (dovuto alla cancellazione di uno studente).

- `before_insert_esami`: Trigger per la creazione di un codice ID unico prima dell'inserimento di un nuovo esame da parte di un professore.

- `controllo_propedeutici_trigger`: Prima dell'iscrizione di uno studente a un esame, viene controllato che siano stati superati tutti gli esami propedeutici.

- `elimina_responsabilità_corso_trigger`: Trigger per la cancellazione di un corso. Quando un corso viene cancellato, vengono rimosse tutte le referenze nella tabella "responsabile_corso".

- `elimina_responsabilità_docente_trigger`: Trigger simmetrico al precedente, attivato quando viene cancellato un docente.
