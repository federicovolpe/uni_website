##Progetto per la creazione di un sito per la gestione delle funzionalità web basiche per l'ateneo

###scelte progettuali per il database
per la creazione del database si è scelto di seguire il seguente schema ER
![alt text](immagini/diagramma.png)

###scelte progettuali per le pagine web
####-> pagina di login
è una semplica pagina di login che permette il riempimento di una form con i dati dell'utente quali email e password.
##### -> la pagina del dispatcher ([dispatcher.php](dispatcher.php))
 quest'ultima si occuperà di reindirizzare l'utente alla propria pagina personale(studente, docente o segreteria), passando le variabili mail e password tramite l'apertura di una sessione.

#### -> pagina principale utente
le pagine di ogni tipologia di utente avranno alcuni aspetti comuni:
- La verifica dell'utente tramite una query di verifica
- Il recupero delle informazioni personali dell'utente contenute nel database
- Il reindirizzamento alla pagina di login qualora uno dei due step precedenti non dovesse andare a buon fine e la segnalazione tramite un codice errore apposito
  
funzionalità che invece saranno differenti:

**pagina studente:**
la pagina studente permetterà di consultare i dati riguardanti l'esito degli esami e di potersi iscrivere ad un esame nuovo
(a patto che sia presente nel proprio corso di studi)

**pagina docente:**
la pagina docente permetterà di poter inserire dei voti per ogni singolo studente inoltre permette, tramite la compilazione di un form, di inserire nuove date per gli esami

**pagina segreteria**
la pagina segreteria.php conterrà diversi form per l'apportazione di modifiche sul database, in particolare saranno presenti 5 form 
- uno per la modifica/inserimento/cancellazione di un utente @docenti.unimi.it
- uno per la modifica/inserimento/cancellazione di un utente @studenti.unimi.it
- uno per la modifica/inserimento/cancellazione di un corso universitario
- uno per la modifica/inserimento/cancellazione di un insegnamento
- uno per la stampa della carriera completa o effettiva di uno studente
