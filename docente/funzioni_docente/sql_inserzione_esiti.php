<?php //tendina per la selezione degli insegnamenti
    //recupero delle variabili settate in post e in sessione
    $matricola = $_POST['m_studente'];
    $esame_nome = $_POST['insegnamento_n'];
    $esito = $_POST['esito'];
    $id_docente = $_SESSION['id'];
    $insegnamento = $_POST['insegnamento_n']; //l'insegnamento è espresso con il nome
    
    //connessione al database
    $db = pg_connect("dbname=unimio host=localhost port=5432");
    if($db){

        //fetch id dell'esame
        $idsql = "SELECT E.id 
                  FROM esami AS E 
                  JOIN insegnamento AS I ON I.id = E.insegnamento 
                  WHERE I.nome = $1";
        $preparato = pg_prepare($db, 'fetch_id_esame', $idsql);
        if($preparato){
            $result = pg_execute($db, 'fetch_id_esame', array($esame_nome));
            if($result){
                //recupero l'id dell'esame
                $id_esame = pg_fetch_assoc($result)['id'];
            }
        }

        //query di inserimento per l'esame
        $inserzione = "INSERT INTO esiti (studente, esame, esito) VALUES ($1, $2, $3)";
        $preparato = pg_prepare($db , "inserzione", $inserzione);

        if($preparato){
            $eseguito = pg_execute($db, "inserzione", array($matricola, $id_esame, $esito));
print('inizio della inserzione del voto: </br> INSERT INTO esiti (studente, esame, esito) VALUES ('.$matricola.', '.$id_esame.', '.$esito.'</br>');

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