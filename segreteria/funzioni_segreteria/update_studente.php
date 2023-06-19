<?php
if (isset($_POST))  {
    // Retrieve the name and surname from the form submission
    $matricola = $_POST['matricola'];
    if (isset($_POST['email'])){
        $email = $_POST['email'];
    }
    if (isset($_POST['password'])){
        $password = $_POST['password'];
    }
    if (isset($_POST['nome'])){
        $nome = $_POST['nome'];
    }
    if (isset($_POST['cognome'])){
        $cognome = $_POST['cognome'];
    }
    if (isset($_POST['corso'])){
        $corso_frequentato = $_POST['corso'];
    }
    $operazione = $_POST['operazione'];

    // connessione al database
    $db = pg_connect("host=localhost port=5432 dbname=unimio");
    //controllo che non ci sia già uno studente con la stessa matricola
    $check = "SELECT 1
              FROM studente
              WHERE matricola = $1";

    $result_check = pg_prepare($db, "check", $check);
    $result_check_rows = pg_execute($db, "check", array($matricola));
    $rows = pg_num_rows($result_check_rows);

    if ($db) {
        switch($operazione){
            case 'aggiungi':
                
                if ($rows === 0){// se il risultato è vuoto allora significa che non esiste nessuno studente già registrato con queste credenziali
                print' preparo';
                    $sql = "INSERT INTO studente (matricola, nome, cognome, email, passwrd, corso_frequentato) 
                            VALUES ($1, $2, $3, $4, $5, $6)";
                    $result = pg_prepare($db, "op_studente", $sql);
                    
                    if ($result) { //se la preparazione della query va a buon fine allora la eseguo
                        $inserito = pg_execute($db, "op_studente", array($matricola, $nome, $cognome, $email, $password, $corso_frequentato));

                        if ($inserito) { //segnalazione con un messaggio di successo
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "lo studente ".$matricola." è stato inserito";
                        } else {     //segnalazione con un messaggio di fallito inserimento
                            $_POST['approved'] = 1;
                            $_POST['msg'] = pg_last_error();
                        }
                    } else { // messaggio di log nella pagina se la preparazione della query non va a buon termine
                        $_POST['approved'] = 1;
                        $_POST['msg'] = pg_last_error();
                    }
            }else{ //esiste già quelcuno con queste credenziali
                $_POST['approved'] = 1;
                $_POST['msg'] = 'risulta già uno studente con questa matricola';
            }
            break;

            case 'modifica':

                if ($rows === 1){ //se il numero di righe è 1 allora lo studente risulta presente
                    //compongo la query in base ai campi che sono settati per essere modificati
                    $contaparametri = 2;
                    $sql = "UPDATE studente 
                            SET ";
                    $array = [];  // Initialize an empty array
                    $array[] = $matricola;
                    if (isset($_POST['nome']) && !empty($_POST['nome'])) {
                        $sql .= "nome = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['nome'];
                    }if (isset($_POST['cognome']) && !empty($_POST['cognome'])) {
                        $sql .= "cognome = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['cognome'];
                    }if (isset($_POST['email']) && !empty($_POST['email'])) {
                        $sql .= "email = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['email'];
                    }if (isset($_POST['password']) && !empty($_POST['password'])) {
                        $sql .= "passwrd = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['password'];
                    }if (isset($_POST['corso']) && !empty($_POST['corso'])) {
                        $sql .= "corso_frequentato = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['corso'];
                    }
                    
                    //togliere l'ultima virgola dalla query
                    if (substr($sql, -1) === ',') {
                        $sql = substr($sql, 0, -1);
                    }
                    $sql = $sql . " WHERE matricola = $1";

                    $result = pg_prepare($db, "op_studente", $sql);
                    $esito_modifica = pg_execute($db, "op_studente", $array);
                    
                    
                    if ($esito_modifica) {//ritorno al sito con un messaggio di successo
                        $_POST['approved'] = 0;
                        $_POST['msg'] = "lo studente ".$matricola." è stato modificato";
                    } else {
                        $_POST['approved'] = 1;
                        $_POST['msg'] = pg_last_error();
                    }
                }else{
                    $_POST['approved'] = 1;
                    $_POST['msg'] = pg_last_error();
                }
            break;


            case 'cancella':

                if ($rows === 1){
                    $sql = "DELETE FROM studente WHERE matricola = $1";
                    $result = pg_prepare($db, "op_studente", $sql);
                
                    if ($result) { //se la preparazione della query va a buon fine allora la eseguo
                        $cancellato = pg_execute($db, "op_studente", array($matricola));

                        if ($cancellato) {//ritorno un messaggio di successo per la cancellazione
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "lo studente ".$matricola." è stato cancellato con successo";
                        } else {
                            $_POST['approved'] = 1;
                            $_POST['msg'] = pg_last_error();
                        }
                    } else {
                        $_POST['approved'] = 1;
                        $_POST['msg'] = pg_last_error();
                    }

                }else{
                    $_POST['approved'] = 1;
                    $_POST['msg'] = pg_last_error();
                }
            break;

            default:
                $_POST['approved'] = 1;
                $_POST['msg'] = "operazione non riconosciuta ".$operazione;
        }

        pg_close($db);
    } else {
        $_POST['approved'] = 1;
        $_POST['msg'] = "connessione al database fallita";
    }
}else{
    $_POST['approved'] = 1;
    $_POST['msg'] = "parametri non sufficienti";
}
?>
