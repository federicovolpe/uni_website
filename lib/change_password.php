<!--  unico file per il cambio password di docente e segreteria, cambia db in base alla tipologia di utente  -->

<?php
    if (session_status() == PHP_SESSION_ACTIVE) {
        //recupero delle variabili di sessione
        $id = $_SESSION['id'];
        $matricola = $_SESSION['matricola'];
        $email = $_SESSION['email'];
        $nuova_password = $_POST['password'];

        //stabilisci connessione con il database
        $db = pg_connect("dbname = unimio host = localhost port = 5432");

        if($db){

            //cambio della password per un utente del tipo segreteria
            if(substr($email, -19) === 'segreteria.unimi.it'){
                $sql = "UPDATE segreteria
                        SET passwrd = $1
                        WHERE id = $2";

                $preparato = pg_prepare($db, "cambio_password", $sql);
                if($preparato){
                    $result = pg_execute($db, "cambio_password", array($nuova_password, $id));
                    
                    if($result){ // se la query è andata a buon fine ritorno un messaggio di successo
                        $_POST['msg'] = "la password di: $id, è stata cambiata in $nuova_password";
                        $_POST['approved'] = 0;
                    
                    }else{    //se la query non è andata a buon fine ritorno un messaggio di errore
                        $_POST['msg'] = "l'esecuzione del cambio password non è andata a buon fine";
                        $_POST['approved'] = 1;
                    }
                }else{
                    $_POST['msg'] = "preparazione della query non riuscita";
                    $_POST['approved'] = 1;
                }

            //cambio della password nel caso di un utente docente
            }else if(substr($email, -16) === 'docenti.unimi.it'){
                $sql = "UPDATE docente
                        SET passwrd = $1
                        WHERE id = $2";

                $preparato = pg_prepare($db, "cambio_password", $sql);
                if($preparato){
                    $result = pg_execute($db, "cambio_password", array($nuova_password, $id));

                    if($result){ // se la query è andata a buon fine ritorno un messaggio di successo
                        $_POST['msg'] = "la password di: $id, è stata cambiata in $nuova_password";
                        $_POST['approved'] = 0;
                    
                    }else{//se la query non è andata a buon fine ritorno un messaggio di errore
                        $_POST['msg'] = "l'esecuzione del cambio password non è andata a buon fine";
                        $_POST['approved'] = 1;
                    }
                }else{
                    $_POST['msg'] = "preparazione della query non riuscita";
                    $_POST['approved'] = 1;
                }

            //cambio password nel caso di un utente studente
            }else if(substr($email, -17) === 'studenti.unimi.it'){

                $sql = "UPDATE studente
                        SET passwrd = $1
                        WHERE matricola = $2";

                $preparato = pg_prepare($db, "cambio_password", $sql);
                if($preparato){
                    $result = pg_execute($db, "cambio_password", array($nuova_password, $matricola));

                    if($result){ // se la query è andata a buon fine ritorno un messaggio di successo
                        $_POST['msg'] = "la password di: $matricola, è stata cambiata in $nuova_password";
                        $_POST['approved'] = 0;
                    
                    }else{//se la query non è andata a buon fine ritorno un messaggio di errore
                        $_POST['msg'] = "l'esecuzione del cambio password non è andata a buon fine";
                        $_POST['approved'] = 1;
                    }
                }else{
                    $_POST['msg'] = "preparazione della query non riuscita";
                    $_POST['approved'] = 1;
                }
            }   
        }else{ //messaggio di errore per una connessione fallita
            $_POST['msg'] = "connessione al db non riuscita";
            $_POST['approved'] = 1;
        } 

    }else{ //ritorno un messaggio di errore nel caso le variabili di sessione non fossero state recuperate
        $_POST['msg'] = "non risultano variabili attive nella sessione";
        $_POST['approved'] = 1;
    }
?>