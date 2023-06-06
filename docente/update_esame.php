<?php
    //include delle funzioni
    session_start();
    include("../lib/functions.php");
    $esame_id = $_SESSION['esame'];
    
    //se è stato premuto il tasto submit allora la variabile chaange è settata a 1
    if($_GET['change'] == 1){
        //obiettivo: modificare la data dell'esame
        
        $newDate = $_POST['new_date'];
        $nuova_data_formattata = date_format(date_create($newDate), 'dmy');
        
    $db = pg_connect("dbname=unimio host=localhost port=5432");
        $sql = "UPDATE esami SET data = $1 WHERE id = $2";
        $preparazione = pg_prepare($db , "update", $sql);

        if($preparazione){
            $result = pg_execute($db, "update", array($nuova_data_formattata,$esame_id));

            if($result){//setto le variabili di riuscta
                $_POST['msg'] = "la modifica dell'esame".$esame_id." è andata a buon fine, nuova data = " . $newDate ;
                $_POST['approved'] = 0;

            }else{// l'esecuzione della query non è andata a buon fine
                $_POST['msg'] = "la modifica dell'esame".$esame_id." NON è andata a buon fine";
                $_POST['approved'] = 1;
            }
        }else{
            $_POST['msg'] = "la preparazione della query NON è andata a buon fine";
            $_POST['approved'] = 1;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <?php include("../lib/head.php"); ?>
<body>
    <?php 
        include("../lib/navbar.php"); 
        messaggi_errore_post2();
        //quando viene aperta la pagina passo la variabile id esame fra le variabili $session per quendo verà effettuata la query di modifica
        $_SESSION['esame'] = $_GET['esame'];
    ?>
    
    <h1>Update Esame</h1>

    <!--                                   form per la raccolta di info per la modifica dell'esame                               -->
        <form method="POST" action="update_esame.php?change=1">
            <label for="new_date">New Date:</label>
            <input type="date" id="new_date" name="new_date" required>
            <input type="submit" value="Update">
        </form>
    </body>
    <?php script_boostrap()?>
</html>