
<?php
    $db = pg_connect("host = localhost port = 5432 dbname = unimio");

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

                if($result == 1){//ritorno un messaggio di errore
                    $_POST['approved'] = 1;
                    $_POST['msg'] = "Esiste già un insegnamento con questo id";
                }else{
                    $insertion_sql = "INSERT INTO corso (id, nome_corso, laurea, descrizione) 
                                        VALUES ($1, $2, $3, $4)";
                    $preparato = pg_prepare($db, "inserzione", $insertion_sql);

                    if($preparato){
                        $inserito = pg_execute($db, "inserzione", array($_POST['id_corso'], $_POST['nome_corso'],$_POST['laurea'], $_POST['descrizione']));
                        
                        //inserzione del docente responsabile
                        $docente_sql = "INSERT INTO responsabile_corso (docente, corso) 
                                        VALUES ($1, $2)";
                        pg_query_params($db, $docente_sql, array($_POST['insegnante_responsabile'], $_POST['id_insegnamento']));

                        if($inserito){//inserimento degli insegnamenti propedeutici
                            
                            foreach ($_POST as $key => $value) {
                                print'inserimento di key: '.$key.' e value: '.$value;
                                $propedeutico_sql = "INSERT INTO propedeuticità (id_insegnamento, id_insegnamento_propedeutico) 
                                                        VALUES ($1, $2)";
                                $result = pg_query_params($db, $propedeutico_sql, array($_POST['id_insegnamento'], $value));
                            }
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "L'insegnamento è stato inserito con successo";
                            //redirezione alla pagina segreteria.php
                            header("Location: ../segreteria.php");
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
                    $modifica_sql = "UPDATE insegnamento 
                                    SET";
                    $array = [];
                    $array[] = $_POST['id_insegnamento'];
                                        //------------  inizio della composizione  --------
                    if(isset($_POST['nome_insegnamento']) && !empty($_POST['nome_insegnamento'])){
                        $modifica_sql .= "nome = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_POST['nome_insegnamento'];
                    }if(isset($_POST['descrizione']) && !empty($_POST['descrizione'])){
                        $modifica_sql .= "descrizione = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_POST['descrizione'];
                    }if(isset($_POST['descrizione']) && !empty($_POST['descrizione'])){
                        $modifica_sql .= "descrizione = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_POST['descrizione'];
                    }if(isset($_POST['corso']) && !empty($_POST['corso'])){
                        $modifica_sql .= "corso = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_POST['corso'];
                    }if(isset($_POST['cfu']) && !empty($_POST['cfu'])){
                        $modifica_sql .= "cfu = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_POST['cfu'];
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
                        $_POST['msg'] = "L'insegnamento è stato modificato con successo";
                        //redirezione alla pagina segreteria.php
                        header("Location: ../segreteria.php");
                    }else{
                        $_POST['approved'] = 1;
                        $_POST['msg'] = pg_last_error();
                        //redirezione alla pagina segreteria.php
                        header("Location: ../segreteria.php");
                    }
                }else{//ritorno un messaggio di errore
                    $_POST['approved'] = 1;
                    $_POST['msg'] = "Non esiste un insegnamento con questo id";
                    //redirezione alla pagina segreteria.php
                    header("Location: ../segreteria.php");
                }
                break;

            case 'elimina': //cancellazione dell'insegnamento con l'id inserito
                    if($result == 1){
                        $cancellazione_sql = "DELETE FROM insegnamento WHERE id = $1";
                        
                        $cancellazione = pg_prepare($db, "cancellazione", $cancellazione_sql);
                        $esito_cancellazione = pg_execute($db, "cancellazione", array($_POST['id_insegnamento']));
                        if($esito_cancellazione){
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "L'insegnamento è stato eliminato con successo";
                            //redirezione alla pagina segreteria.php
                            header("Location: ../segreteria.php");
                        }else{
                            $_POST['approved'] = 1;
                            $_POST['msg'] = pg_last_error();
                        }
                    }else{//ritorno un messaggio di errore
                        $_POST['approved'] = 1;
                        $_POST['msg'] = "Non esiste un insegnamento con questo id";
                        //redirezione alla pagina segreteria.php
                        header("Location: ../segreteria.php");
                    }
                break;
            default:
                $_POST['approved'] = 1;
                $_POST['msg'] = "Errore nella scelta dell'operazione";
                //redirezione alla pagina segreteria.php
                header("Location: ../segreteria.php");

                break;
        }
    }else{
        $_POST['msg'] = "Errore di connessione al database";
        $_POST['approved'] = 1;
        //redirezione alla pagina segreteria.php
        header("Location: ../segreteria.php");
    }
?>