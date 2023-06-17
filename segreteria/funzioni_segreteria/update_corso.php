<?php
    $db = pg_connect("host = localhost port = 5432 dbname = unimio");
print'entrato nello script php----------------------------------<br>';
    //control
    if($db){
        //swithc eseguito sul parametro in sessione perchè quello in post con l'operazione di modifica si cancellerebbe
        //verifica che non esistano già corsi con lo stesso id
        $check_sql = "SELECT 1
            FROM corso
            WHERE id = $1";
        $result = pg_prepare($db, "check", $check_sql);
        $result = pg_execute($db, "check", array($_POST['id_corso']));
        
        switch ($_POST['operazione']){
            case 'aggiungi':
                print'entrato nell\'inserzione<br>';
                if($result == 1){//ritorno un messaggio di errore
                    $_POST['approved'] = 1;
                    $_POST['msg'] = "Esiste già un insegnamento con questo id";
                }else{
                    $insertion_sql = "INSERT INTO corso (id, nome_corso, laurea, descrizione) 
                                        VALUES ($1, $2, $3, $4)";
                    $preparato = pg_prepare($db, "inserzione", $insertion_sql);

                    if($preparato){
                        $inserito = pg_execute($db, "inserzione", array($_POST['id_corso'], $_POST['nome_corso'],$_POST['laurea'], $_POST['descrizione_corso']));
                        
                        //inserzione del docente responsabile
                        $docente_sql = "INSERT INTO responsabile_corso (docente, corso) 
                                        VALUES ($1, $2)";
                        pg_query_params($db, $docente_sql, array($_POST['docente_responsabile'], $_POST['id_corso']));

                        if($inserito){
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "L'insegnamento è stato inserito con successo";
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
                
                if($result = 1){
                    //composizione della query in base ai parametri inseriti
                    $contaparametri = 2;
                    $modifica_sql = "UPDATE corso 
                                    SET";
                    $array = [];
                    $array[] = $_POST['id_corso'];
                                        //------------  inizio della composizione  --------
                    if(isset($_POST['nome_corso']) && !empty($_POST['nome_corso'])){
                        $modifica_sql .= "nome_corso = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_POST['nome_corso'];
                    }if(isset($_POST['descrizione_corso']) && !empty($_POST['descrizione_corso'])){
                        $modifica_sql .= "descrizione = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_POST['descrizione_corso'];
                    }if(isset($_POST['laurea']) && !empty($_POST['laurea'])){
                        $modifica_sql .= "laurea = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_POST['laurea'];
                    }

                    //togliere l'ultima virgola dalla query
                    if(substr($modifica_sql, -1) === ','){
                        $modifica_sql = substr($modifica_sql, 0, -1);
                    }
                    $modifica_sql = $modifica_sql ." WHERE id = $1";
                    
                    $preparato = pg_prepare($db, "modifica", $modifica_sql);
                    $esito_modifica = pg_execute($db, "modifica", $array);

                    if($esito_modifica){
                        $_POST['approved'] = 0;
                        $_POST['msg'] = "Il corso è stato modificato con successo";
                    }else{
                        $_POST['approved'] = 1;
                        $_POST['msg'] = pg_last_error();
                    }
                }else{//ritorno un messaggio di errore
                    $_POST['approved'] = 1;
                    $_POST['msg'] = "Non esiste un corso con questo id";
                }
                break;

            case 'cancella': //cancellazione dell'insegnamento con l'id inserito
                    if($result){
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
                        $_POST['msg'] = "Non esiste un corso con questo id1 ".$_POST['id_corso']."<br>";
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