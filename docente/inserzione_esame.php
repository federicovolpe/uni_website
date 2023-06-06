<?php
//obiettivo: modificare la data dell'esame
session_start();
    $id_docente = $_SESSION['id'];
    $insegnamento = $_POST['insegnamento'];
    $date = $_POST['date'];
    $nuova_data_formattata = date_format(date_create($date), 'dmy');
    
    $db = pg_connect("dbname=unimio host=localhost port=5432");
        $sql = "INSERT INTO esami (insegnamento, docente, data) VALUES ($1, $2, $3)";
        $preparazione = pg_prepare($db , "update", $sql);

        if($preparazione){
            pg_execute($db, "update", array($insegnamento, $id_docente, $nuova_data_formattata));
            print("update dell'esame");

            $url_errore ="update_esame.php?approved=" . urlencode(0) . "&msg="  . urlencode("esame inserito con successo");
            header("Location: " . $url_errore);
            exit;
        }else{
            $url_errore ="update_esame.php?approved=" . urlencode(1) . "&msg="  . urlencode("la modifica dell'esame non è andata a buon fine");
            header("Location: " . $url_errore);
            exit;
        }

?>