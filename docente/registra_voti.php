<?php
    if(isset($_POST)){
        $matricola = $_POST['matricola'];
        $esame = $_POST['esame'];
        $esito = $_POST['esito'];
        
        //stabilisci connessione con il database
        $db = pg_connect("dbname = unimio host = localhost port = 5432");
        if($db){
            $sql = "INSERT INTO esiti (studente, esame, esito)
                    VALUES($1,$2,$3)";
            $preparato = pg_prepare($db, "inserzione_voto", $sql);
            if($preparato){
                $result = pg_execute($db, "inserzione_voto", array($matricola, $esame, $esito));
                if($result){
                    print("esame: $esame, con esito: $esito inserito con successo allo studente: $matricola");

                }else{
                    print("l'esecuzione della query non è andata a buon fine");
                }
            }else{
                print("preparazione della query non riuscita");
            }
        }else{
            print("connessione al db non riuscita");
        }
    }else{
        print("POST risulta non settato");
    }
?>