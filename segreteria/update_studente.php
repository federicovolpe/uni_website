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
                            echo "lo studente è stato inserito";
                            $url_successo ="segreteria.php?approved=" . urlencode(0);
                            header("Location: " . $url_successo);
                            exit;
                        } else {     //segnalazione con un messaggio di fallito inserimento
                            echo "lo studente non è stato inserito";
                            $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg=" . urlencode("lo studente non è stato inserito");
                            header("Location: " . $url_errore);
                            exit;
                        }
                    } else { // messaggio di log nella pagina se la preparazione della query non va a buon termine
                        echo "qualcosa è andato storto nella preparazione della query.";
                    }
            }else{ //esiste già quelcuno con queste credenziali
                echo "lo studente non è stato inserito";
                $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg=" . urlencode("Risulta già uno studente con la stessa matricola o email");
                header("Location: " . $url_errore);
                exit;
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
                        $url_successo ="segreteria.php?approved=" . urlencode(0);
                        header("Location: " . $url_successo);
                        exit;
                    } else {
                        $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg="  . urlencode("la modifica dello studente non è andata a buon fine");
                        header("Location: " . $url_errore);
                        exit;
                    }
                }else{
                    $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg=" . urlencode("Non risulta uno studente con questa matricola o email");
                    header("Location: " . $url_errore);
                    exit;
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

                        if ($cancellato) {
                            echo "lo studente è stato cancellato";
                            $url_successo ="segreteria.php?approved=" . urlencode(0);
                            header("Location: " . $url_successo);
                            exit;
                        } else {
                            echo "lo studente non è stato cancellato";
                            $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg="  . urlencode("la cancellazione dello studente non è andata a buon fine");;
                            header("Location: " . $url_errore);
                            exit;
                        }
                    } else {
                        echo "qualcosa è andato storto nella preparazione della query.";
                    }

                }else{
                    $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg=" . urlencode("Non risulta uno studente con questa matricola o email");
                    header("Location: " . $url_errore);
                    exit;
                }

            default:
                echo("qualcosa è andato storto nella selezione dell'operazione");
                break;
        }

        pg_close($db);
    } else {
        echo "connessione al database fallita";
    }
}else{
    echo("il $_POST non è settato!");
}
?>
