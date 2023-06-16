##Progetto per la creazione di un sito per la gestione delle funzionalità web basiche per l'ateneo

###scelte progettuali per il database
per la creazione del database si è scelto di seguire il seguente schema ER
![alt text](../immagini/diagramma.png)

###scelte progettuali per le pagine web
<div style="display: flex; align-items: flex-start;">
    <div style="flex-grow: 1;">
        <h3>pagina di login</h3>
        <p>È una semplice pagina di login che permette il riempimento di una form con i dati dell'utente 
        quali email e password.</p>
    </div>
    <img src="../media/login.gif" alt="GIF" style="margin-left: 10px; width: 50%">
</div>
nella pagina di login è presente un flag che indica se è stato tentato un accesso, se cosi fosse viene chiamato lo script:

- *([dispatcher.php](dispatcher.php))*
 composta da solo codice php si occuperà di reindirizzare l'utente alla propria pagina personale(studente, docente o segreteria), passando le variabili mail e password tramite l'apertura di una sessione.
 il dispatching verrà eseguito tenendo conto del finale della email dell'utente.

#### -> pagina principale utente
le pagine di ogni tipologia di utente avranno alcuni aspetti comuni:
- La verifica dell'utente tramite una query di verifica
- Il recupero delle informazioni personali dell'utente contenute nel database
- Il reindirizzamento alla pagina di login qualora uno dei due step precedenti non dovesse andare a buon fine e la segnalazione tramite un codice errore apposito
- il codice php per un eventuale cambio password previsto per tutti gli utenti
- importazione di file come head, navbar, link agli script di bootstrap, display di messaggi di errore
  

**pagina studente:**
la pagina studente permetterà di consultare i dati riguardanti l'esito degli esami (che essi riguardino la carriera completa oppure valida):
- *([carriera_valida.php](carriera_valida.php))*
    viene esposto il contenuto della carriera valida dello studente con cui è stato eseguito il login
    (viene mostrato direttamente nella pagina principale dello studente).
- *([esiti_esami.php](esiti_esami.php))*
    in questa pagina accessibile tramite un link dalla homepage dello studente verranno mostrati in una tabella simile alla precedente tutti gli esiti ottenuti dallo studente.

sulla destra della pagina verranno mostrate le informazioni riguardanti *tutti i corsi dell'università disponibili* (compreso l'elenco di tutti gli insegnamenti).

vi è la possibilità inoltre di potersi iscrivere ad un esame nuovo tramite un link "prenota unesame" che porta alla pagina *([prenota_esame.php](prenota_esame.php))*
- *([prenota_esame.php](prenota_esame.php))*
    questa pagina mostra allo studente una tabella con tutti gli esami che sono stati programmati dai professori del rispettivo corso a cui si è iscritti con qualche informazione aggiuntiva(come data o professore responsabile).
    i pulsanti per la iscrizione prenderanno una forma differente se lo studente risulta già iscritto all'esame o meno, tramite gli stessi sarà anche possibile annullare l'iscrizione ad un esame.
    la pagina è provvista di messaggi di errore qual'ora lo studente non risulti aver passato tutti gli insegnamenti propedeutici previsti per un determinato esame.

infine in fondo alla pagina verrà sempre mostrato un pulsatne per la rinuncia agli studi, quando questo verrà premuto lo studente corrente verrà cancellato dalla tabella degli studenti e spostato nella tabella storico_studente

**pagina docente:**
la pagina docente mostra come prima cosa la tabella che rappresenterà il calendario degli esami del professore.
questa mostrerà l'insegnamento, la data, due pulsanti per la modifica (connesso allo script *([update_esame.php](update_esame.php))*) o la cancellazione del suddetto esame (connesso allo script *([cancella_esame.php](cancella_esame.php))*).
successivamente verrà mostrato un form generato dalla pagina:
- *([form_inserisci_esame.php](form_inserisci_esame.php))*
    ha il compito di raccogliere le informazioni per un nuovo esame, quali: insegnamento, data dell'esame
    il compito dell'inserimento nel database viene poi delegato al file *([inserzione_esame.php](inserzione_esame.php))* che si occuperà fra l'altro di riportare eventuale messaggi di errore o di successo legati all'esecuzione della query alla pagina precedente (ad esempio la presenza di un esame con lo stesso id già inserito nel database).

successivamente verrà mostrato un form per l'inserzione degli esiti, anche questo ha la sua pagina dedicata:
- *([form_inserzione_esiti.php](form_inserzione_esiti.php))*
    questo si occupa della raccolta di dati quali: la matricola dello studente interessato; l'esame; l'esito da assegnare. l'esecuzione della operazione di inserimento come nel caso precedente viene delegata alla pagina *([sql_inserzione_esiti.php](sql_inserzione_esiti.php))* quest'ultimo si occupera di effettuare tutte le verifiche necessarie per l'inserzione (come la verifica dell'iscrizione dello studente a quell'esame, o la correttezza dell'id stuente).


**pagina segreteria**
la pagina segreteria.php conterrà diversi form per l'apportazione di modifiche sul database, in particolare saranno presenti 5 form 
- **form per la gestione dei docenti**
    per la modifica/inserimento/cancellazione di un utente @docenti.unimi.it
    raccoglie le informazioni: id, email, nome, cognome, password, operazione
    in particolare il parametro "operazione" può assumere 3 diversi valori (inserisci, modifica, cancella) e servirà per differenziare il comportamento del file:
    - *([update_docente.php](update_docente.php))*
    come prima cosa viene eseguita una query che stabilisce l'esistenza o meno di uno studente con l'id interessato
        - con il parametro "inserisci" verranno richiesti tutti i parametri essenziali per l'esecuzione di una query di inserimento nella tabella docente
        - con il parametro "modifica" verrà prima composta una query con dei check sulle variabili che possono interessare una modifica prima di essere eseguita
        - con il parametro "cancella" viene eseguita una semplice query di delete sul database
        
    Alla fine di ogni operazione vengono comunque generati messaggi che indicano l'esito dell'esecuzione delle operazioni
    questi messaggi verranno mostrati nella pagina segreteria.php


- *([update_studente.php](update_studente.php))* 
    per la modifica/inserimento/cancellazione di un utente @studenti.unimi.it

- *([update_corso.php](update_corso.php))* 
    per la modifica/inserimento/cancellazione di un corso universitario

- *([update_insegnamento.php](update_insegnametno.php))* 
    per la modifica/inserimento/cancellazione di un insegnamento

- *([carriera_studente.php](carriera_studente.php))* 
    per la stampa della carriera completa o effettiva di uno studente
