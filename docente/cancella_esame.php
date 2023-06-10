<?php
//obiettivo: cancellare l'esame specificato in $esame
    $esame_id = $_GET['cancella_esame']; 
    print("esame : ". $esame_id."</br>");
    
    $db = pg_connect("dbname=unimio host=localhost port=5432");
    if($db){
        $sql = "DELETE FROM esami 
                WHERE id = $1";
        $preparazione = pg_prepare($db , "cancellazione", $sql);
        if($preparazione){
            print'query preparata con successo';
            $eseguito = pg_execute($db, "cancellazione", array($esame_id));
        
            if($eseguito){
                
                //ritorno un messaggio di successo
                $_POST['msg'] = "esame". $esame_id ." cancellato con successo";
                $_POST['approved'] = 0;
            }
        }else{//ritorno un messaggio di errore
            $_POST['msg'] = pg_last_error();
            $_POST['approved'] = 1;
        }
    }else{
        $_POST['msg'] = "connessione al database fallita";
        $_POST['approved'] = 1;
    }
?>