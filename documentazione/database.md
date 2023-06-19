# Progetto Database Unimi

Ho deciso di implementare un unico schema chiamato "unimi_it" per il database denominato "unimio". Questo schema conterrà tutte le tabelle necessarie per il progetto.

Lo schema entità-relazione è stato strutturato come mostrato nell'immagine seguente:

<div style="display: flex; justify-content: center;">
    <img src="../media/er_schema.png" alt="Image" style="width: 90%;border: 2px solid blue;">
</div>

Successivamente alla creazione dello schema ER, ho apportato alcune migliorie al fine di ridurre il numero di tabelle necessarie. In particolare, ho deciso di incorporare le relazioni [1-1] tra due entità nella stessa tabella, sotto forma di attributo che fa riferimento all'altra entità. Questo ha permesso di risparmiare una tabella per la relazione.

L'entità "segreteria", anche se non ha relazioni con le altre entità, ha la possibilità di definire nuovi utenti e nuovi corsi. Questa funzionalità è stata implementata utilizzando le frecce nello schema.

## Schema Logico
<div style="display: flex; justify-content: center;">
    <img src="../media/diagramma.png" alt="Image" style="width: 90%;border: 2px solid blue;">
</div>
<br>
Il database è stato popolato con dati realistici al fine di consentire l'esplorazione delle varie funzionalità dell'applicazione web. ovvero:<br>
- 16 studenti iscritti a tutti i differenti corsi
<br>- 10 docenti di cui responsabili di corsi e di insegnamenti(massimo 3)
<br>- 5 utenti di segreteria
<br>- 5 corsi differenti, ciascuno composto da una media di 5 insegnamenti
<br>- tabella iscrizioni con 7 iscrizioni per le prove
<br>- 1 propedeuticità per il corso programmazione2
<br>- 1 studente con 1 voto nella sezione storico studente e storico_carriera derivante dalla prova
<br>
<br><hr></hr>
Di seguito vengono elencati i trigger che sono stati implementati:



- `insegnamenti_responsabile_trigger`: impedisce a un professore di diventare responsabile di più di tre insegnamenti.
<img src="../media/dimostrazione_trigger/n_insegnamenti_responsabile.gif" alt="GIF" style="margin-right: 10px; width: 50%">

- `verifica_iscrizione_trigger`: Prima di inserire un esito per un esame, viene verificato che lo studente sia effettivamente iscritto a quell'esame.
<img src="../media/dimostrazione_trigger/verifica_iscrizione.gif" alt="GIF" style="margin-right: 10px; width: 50%">

- `cancellazione_studente_trigger`: Prima di cancellare uno studente, i suoi dati vengono spostati nel database.
<img src="../media/dimostrazione_trigger/cancellazione_studente.gif" alt="GIF" style="margin-right: 10px; width: 50%">

- `salvataggio_esiti_trigger`: per il salvataggio dei dati riguardanti un esito cancellato (dovuto alla cancellazione di uno studente). dimostrazione nel trigger precedente

- `before_insert_esami`: per la creazione di un codice ID unico prima dell'inserimento di un nuovo esame da parte di un professore.
<img src="../media/dimostrazione_trigger/generate_esami_id.gif" alt="GIF" style="margin-right: 10px; width: 50%">

- `controllo_propedeutici_trigger`: Prima dell'iscrizione di uno studente a un esame, viene controllato che siano stati superati tutti gli esami propedeutici.
<img src="../media/dimostrazione_trigger/controllo_propedeutici.gif" alt="GIF" style="margin-right: 10px; width: 50%">

- `esami_giornalieri`: prima dell'inserzione di un nuovo esame viene controllato che la data di questo non sia uguale a quella di un altro esame dello stesso corso
<img src="../media/dimostrazione_trigger/esami_giornalieri.gif" alt="GIF" style="margin-right: 10px; width: 50%">