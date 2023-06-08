<?php

    $id_docente = $_SESSION['id'];
    $insegnamento = $_POST['insegnamento'];
    $date = $_POST['data'];
    $nuova_data_formattata = date_format(date_create($date), 'dmy');
    
    $db = pg_connect("dbname=unimio host=localhost port=5432");
        $sql = "INSERT INTO esami (insegnamento, docente, data) VALUES ($1, $2, $3)";
        $preparazione = pg_prepare($db , "update", $sql);

        if($preparazione){
            pg_execute($db, "update", array($insegnamento, $id_docente, $nuova_data_formattata));
            $_POST['msg'] = "esame inserito con successo";
            $_POST['approved'] = 0;

        }else{
            $_POST['msg'] = pg_last_error();
            $_POST['approved'] = 0;
        }

?>