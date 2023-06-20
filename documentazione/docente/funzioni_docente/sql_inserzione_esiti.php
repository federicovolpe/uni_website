<!--  script php che si occupa dell'inserzione di un voto da parte di un docente per un determinato studente  -->

<?php
    //recupero delle variabili settate in post e in sessione
    $matricola = $_POST['m_studente'];
    $id_esame = $_POST['esame_id'];
    $esito = $_POST['esito'];
    $id_docente = $_SESSION['id'];
    
    //connessione al database
    $db = pg_connect("dbname=unimio host=localhost port=5432");
    if($db){

        //query di inserimento per l'esame
        $inserzione = "INSERT INTO esiti (studente, esame, esito) VALUES ($1, $2, $3)";
        $preparato = pg_prepare($db , "inserzione", $inserzione);

        if($preparato){
            $eseguito = pg_execute($db, "inserzione", array($matricola, $id_esame, $esito));

            if($eseguito){ //se l'inserzione è andata a buon fine rilascio un messaggio di successo
                $_POST['msg'] = 'esito inserito con successo </br> matricola: '.$matricola.'</br> id esame: '.$id_esame.'</br> esito: '.$esito.'</br>';
                $_POST['approved'] = 0;
            }else{
                $_POST['msg'] = "errore in inserimento: ". pg_last_error();
                $_POST['approved'] = 1;
            }
        }else{
            $_POST['msg'] = pg_last_error();
            $_POST['approved'] = 1;
        }
    }else{
        $_POST['msg'] = "connessione al database fallita";
        $_POST['approved'] = 1;
    }
?>