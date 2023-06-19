<!-- script per la modifica cancellazione o inserimento di un corso universitario -->

<?php
    $db = pg_connect("host = localhost port = 5432 dbname = unimio");
    if($db){
        
        //verifica che non esistano già corsi con lo stesso id
        $check_sql = "SELECT 1
            FROM corso
            WHERE id = $1";
        $result = pg_prepare($db, "check", $check_sql);
        $result = pg_execute($db, "check", array($_POST['id_corso']));
        $rows = pg_num_rows($result_check_rows);
        
        //swithc eseguito sul parametro in sessione perchè quello in post con l'operazione di modifica si cancellerebbe
        switch ($_POST['operazione']){
            case 'aggiungi':

                if($rows === 1){//ritorno un messaggio di errore
                    $_POST['approved'] = 1;
                    $_POST['msg'] = "Esiste già un corso con questo id";
                }else{

                    //query di inserzione del corso
                    $insertion_sql = "INSERT INTO corso (id, nome_corso, laurea, descrizione, responsabile) 
                                        VALUES ($1, $2, $3, $4, $5)";
                    $preparato = pg_prepare($db, "inserzione", $insertion_sql);

                    if($preparato){
                        $inserito = pg_execute($db, "inserzione", array($_POST['id_corso'], $_POST['nome_corso'],$_POST['laurea'], $_POST['descrizione_corso'], $_POST['docente_responsabile']));

                        if($inserito){
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "il corso ".$id." è stato inserito con successo";
                        }else{
                            $_POST['approved'] = 1;
                            $_POST['msg'] = pg_last_error();
                        }
                    }else{
                        $_POST['approved'] = 1;
                        $_POST['msg'] = pg_last_error();
                    }
                }
                break;

            case 'modifica':
                
                if($rows === 1){

                    //composizione della query in base ai parametri inseriti
                    $contaparametri = 2;
                    $modifica_sql = "UPDATE corso 
                                    SET ";
                    $array = [];
                    $array[] = $_POST['id_corso'];

                        //------------  inizio della composizione  --------
                    if(isset($_POST['nome_corso']) && !empty($_POST['nome_corso'])){
                        $modifica_sql .= "nome_corso = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['nome_corso'];
                    }if(isset($_POST['descrizione_corso']) && !empty($_POST['descrizione_corso'])){
                        $modifica_sql .= "descrizione = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['descrizione_corso'];
                    }if(isset($_POST['laurea']) && !empty($_POST['laurea'])){
                        $modifica_sql .= "laurea = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['laurea'];
                    }

                    //togliere l'ultima virgola dalla query
                    if(substr($modifica_sql, -1) === ','){
                        $modifica_sql = substr($modifica_sql, 0, -1);
                    }
                    $modifica_sql = $modifica_sql ." WHERE id = $1";
                    
                    $preparato = pg_prepare($db, "modifica", $modifica_sql);
                    if($preparato){
                        $esito_modifica = pg_execute($db, "modifica", $array);

                        if($esito_modifica){
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "Il corso ".$id." è stato modificato con successo";
                        }else{
                            $_POST['approved'] = 1;
                            $_POST['msg'] = pg_last_error();
                        }
                    }else{
                        $_POST['approved'] = 1;
                            $_POST['msg'] = 'qualcosa è andato storto nella preparazione della query: '.$modifica_sql;
                    }
                }else{//ritorno un messaggio di errore
                    $_POST['approved'] = 1;
                    $_POST['msg'] = "Non esiste un corso con questo id";
                }
                break;

            case 'cancella': //cancellazione del corso con l'id inserito
                    if($rows === 1){
                        $cancellazione_sql = "DELETE FROM corso WHERE id = $1";
                        
                        $cancellazione = pg_prepare($db, "cancellazione", $cancellazione_sql);
                        $esito_cancellazione = pg_execute($db, "cancellazione", array($_POST['id_corso']));
                        if($esito_cancellazione){
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "Il corso è stato eliminato con successo";
                        }else{
                            $_POST['approved'] = 1;
                            $_POST['msg'] = pg_last_error();
                        }
                    }else{//ritorno un messaggio di errore
                        $_POST['approved'] = 1;
                        $_POST['msg'] = "Non esiste un corso con questo id ".$id."<br>";
                    }
                break;

            default:
                $_POST['approved'] = 1;
                $_POST['msg'] = "Errore nella scelta dell'operazione";

                break;
        }
    }else{
        $_POST['msg'] = "Errore di connessione al database";
        $_POST['approved'] = 1;
    }
?>