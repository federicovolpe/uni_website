<?php
//obiettivo: cancellare l'esame specificato in $esame
    $esame_id = $_GET['esame']; //non so perchè devo prelevare da insegnamento
    $operazione = $_GET['operazione'];
    print("esame : ". $esame_id."</br>");
    print("operazione: ". $operazione);
    
    $db = pg_connect("dbname=unimio host=localhost port=5432");
            $sql = "DELETE FROM esami 
                    WHERE id = $1";
            $preparazione = pg_prepare($db , "cancellazione", $sql);
            if($preparazione){
                //pg_execute($db, "cancellazione", array($esame_id));
                print("cancellazione dell'esame");

                $url_errore ="docente.php?approved=" . urlencode(0) ;
                header("Location: " . $url_errore);
                exit;
            }else{
                print("qualcosa è andato storto nella preparazione della query");
                $url_errore ="docente.php?approved=" . urlencode(1) . "&msg="  . urlencode("la cancellazione dell'esame non è andata a buon fine");
                header("Location: " . $url_errore);
                exit;
            }
?>