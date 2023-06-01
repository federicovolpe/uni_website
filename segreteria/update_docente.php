<?php
if (isset($_POST))  {
    // Retrieve the name and surname from the form submission
    $id = $_POST['id'];
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
    $operazione = $_POST['operazione'];

    // connessione al database
    $db = pg_connect("host=localhost port=5432 dbname=unimio");

    if ($db) {
        switch($operazione){
            case 'aggiungi':
                //controllo che non ci sia già uno docente con la stessa id
                $check = "SELECT 1
                    FROM docente
                    WHERE id = $1 OR email = $2";

                $result_check = pg_prepare($db, "check", $sql);
                $result_check = pg_execute($db, "check", array($id,$email));

            if(empty($result_check)){// se il risultato è vuoto allora significa che non esiste nessuno docente già registrato con queste credenziali
                
                    $sql = "INSERT INTO docente (id, nome, cognome, email, passwrd) 
                            VALUES ($1, $2, $3, $4, $5)";
                    $result = pg_prepare($db, "op_docente", $sql);
                    
                    if ($result) { //se la preparazione della query va a buon fine allora la eseguo
                        $inserito = pg_execute($db, "op_docente", array($id, $nome, $cognome, $email, $password));

                        if ($inserito) { //segnalazione con un messaggio di successo
                            echo "lo docente è stato inserito";
                            $url_successo ="segreteria.php?approved=" . urlencode(0);
                            header("Location: " . $url_successo);
                            exit;
                        } else {     //segnalazione con un messaggio di fallito inserimento
                            echo "lo docente non è stato inserito";
                            $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg=" . urlencode("lo docente non è stato inserito");
                            header("Location: " . $url_errore);
                            exit;
                        }
                    } else { // messaggio di log nella pagina se la preparazione della query non va a buon termine
                        echo "qualcosa è andato storto nella preparazione della query.";
                    }
            }else{ //esiste già quelcuno con queste credenziali
                echo "lo docente non è stato inserito";
                $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg=" . urlencode("Risulta già uno docente con la stessa id o email");
                header("Location: " . $url_errore);
                exit;
            }
            break;

            case 'modifica':
                print("entrato nella modifica / ");

                //query per verificare la presenza del suddetto docente
                $check = "  SELECT 1
                            FROM docente
                            WHERE id = $1 AND email = $2";

                $result_check = pg_prepare($db, "check", $check);
                $result_check = pg_execute($db, "check", array($id,$email));

                if($result_check = 1){ //se il numero di righe è 1 allora lo docente risulta presente
                    $sql = "UPDATE docente 
                            SET nome              = $2, 
                                cognome           = $3, 
                                email             = $4, 
                                passwrd           = $5
                            WHERE id = $1";

                    $result = pg_prepare($db, "op_docente", $sql);
                    $esito_modifica = pg_execute($db, "op_docente", array($id, $nome, $cognome, $email, $password));
                    
                    
                    if ($esito_modifica) {//ritorno al sito con un messaggio di successo
                        $url_successo ="segreteria.php?approved=" . urlencode(0);
                        header("Location: " . $url_successo);
                        exit;
                    } else {
                        $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg="  . urlencode("la modifica dello docente non è andata a buon fine");
                        header("Location: " . $url_errore);
                        exit;
                    }
                }else{
                    $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg=" . urlencode("Non risulta uno docente con questa id o email");
                    header("Location: " . $url_errore);
                    exit;
                }
            break;


            case 'cancella':
                $check = "  SELECT 1
                            FROM docente
                            WHERE id = $1 AND email = $2";

                $result_check = pg_prepare($db, "check", $check);
                $result_check = pg_execute($db, "check", array($id,$email));

                if($result_check = 1){

                    $sql = "DELETE FROM docente WHERE id = $1 AND email = $2";
                    $result = pg_prepare($db, "op_docente", $sql);
                
                    if ($result) { //se la preparazione della query va a buon fine allora la eseguo
                        $cancellato = pg_execute($db, "op_docente", array($id, $email));

                        if ($cancellato) {
                            echo "lo docente è stato cancellato";
                            $url_successo ="segreteria.php?approved=" . urlencode(0);
                            header("Location: " . $url_successo);
                            exit;
                        } else {
                            echo "lo docente non è stato cancellato";
                            $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg="  . urlencode("la cancellazione dello docente non è andata a buon fine");;
                            header("Location: " . $url_errore);
                            exit;
                        }
                    } else {
                        echo "qualcosa è andato storto nella preparazione della query.";
                    }

                }else{
                    $url_errore ="segreteria.php?approved=" . urlencode(1) . "&msg=" . urlencode("Non risulta uno docente con questa id o email");
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
