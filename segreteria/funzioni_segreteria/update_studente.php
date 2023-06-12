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
    if (isset($_POST['corso_frequentato'])){
        $corso_frequentato = $_POST['corso_frequentato'];
    }
    $operazione = $_POST['operazione'];

    // connessione al database
    $db = pg_connect("host=localhost port=5432 dbname=unimio");

    if ($db) {
        switch($operazione){
            case 'aggiungi':
                //controllo che non ci sia già uno studente con la stessa matricola
                $check = "SELECT 1
                    FROM studente
                    WHERE matricola = $1 OR email = $2";

                $result_check = pg_prepare($db, "check", $sql);
                $result_check = pg_execute($db, "check", array($matricola,$email));

            if(empty($result_check)){// se il risultato è vuoto allora significa che non esiste nessuno studente già registrato con queste credenziali
                
                    $sql = "INSERT INTO studente (matricola, nome, cognome, email, passwrd, corso_frequentato) 
                            VALUES ($1, $2, $3, $4, $5, $6)";
                    $result = pg_prepare($db, "op_studente", $sql);
                    
                    if ($result) { //se la preparazione della query va a buon fine allora la eseguo
                        $inserito = pg_execute($db, "op_studente", array($matricola, $nome, $cognome, $email, $password, $corso_frequentato));

                        if ($inserito) { //segnalazione con un messaggio di successo
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "lo studente è stato inserito";
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
                $_POST['msg'] = pg_last_error();
            }
            break;

            case 'modifica':
                print("entrato nella modifica / ");

                //query per verificare la presenza del suddetto studente
                $check = "  SELECT 1
                            FROM studente
                            WHERE matricola = $1 AND email = $2";

                $result_check = pg_prepare($db, "check", $check);
                $result_check = pg_execute($db, "check", array($matricola,$email));

                if($result_check = 1){ //se il numero di righe è 1 allora lo studente risulta presente
                    $sql = "UPDATE studente 
                            SET nome              = $2, 
                                cognome           = $3, 
                                email             = $4, 
                                passwrd           = $5, 
                                corso_frequentato = $6
                            WHERE matricola = $1";

                    $result = pg_prepare($db, "op_studente", $sql);
                    $esito_modifica = pg_execute($db, "op_studente", array($matricola, $nome, $cognome, $email, $password, $corso_frequentato,));
                    
                    
                    if ($esito_modifica) {//ritorno al sito con un messaggio di successo
                        $_POST['approved'] = 0;
                        $_POST['msg'] = "lo studente è stato modificato";
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
                $check = "  SELECT 1
                            FROM studente
                            WHERE matricola = $1 AND email = $2";

                $result_check = pg_prepare($db, "check", $check);
                $result_check = pg_execute($db, "check", array($matricola,$email));

                if($result_check = 1){

                    $sql = "DELETE FROM studente WHERE matricola = $1 AND email = $2";
                    $result = pg_prepare($db, "op_studente", $sql);
                
                    if ($result) { //se la preparazione della query va a buon fine allora la eseguo
                        $cancellato = pg_execute($db, "op_studente", array($matricola, $email));

                        if ($cancellato) {//ritorno un messaggio di successo per la cancellazione
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "lo studente è stato cancellato con successo";
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

            default:
                $_POST['approved'] = 1;
                $_POST['msg'] = "operazione non riconosciuta";
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
