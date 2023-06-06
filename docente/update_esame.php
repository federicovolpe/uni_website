<?php
    //include delle funzioni
    session_start();
    include("../lib/functions.php");
    $esame_id = $_SESSION['esame'];
    
    //se è stato premuto il tasto submit allora la variabile chaange è settata a 1
    if($_GET['change'] == 1){
        //obiettivo: modificare la data dell'esame
        
        $newDate = $_POST['new_date'];
        print("esame : ". $esame_id."</br>");
        $nuova_data_formattata = date_format(date_create($newDate), 'dmy');
        print("data : ". $formattedDate."</br>");
        
    $db = pg_connect("dbname=unimio host=localhost port=5432");
        $sql = "UPDATE esami SET data = $1 WHERE id = $2";
        $preparazione = pg_prepare($db , "update", $sql);

        if($preparazione){
            $result = pg_execute($db, "update", array($nuova_data_formattata,$esame_id));

            if($result){
                $url_errore ="update_esame.php?approved=" . urlencode(0) . "&msg="  . urlencode("la modifica dell'esame".$esame_id." è andata a buon fine, nuova data = " . $newDate ) ;
                header("Location: " . $url_errore);
                exit;
            }else{// l'esecuzione della query non è andata a buon fine
                $url_errore ="update_esame.php?approved=" . urlencode(1) . "&msg="  . urlencode("la modifica dell'esame non è andata a buon fine");
                header("Location: " . $url_errore);
                exit;
            }
        }else{
            print("qualcosa è andato storto nella preparazione della query");
            $url_errore ="update_esame.php?approved=" . urlencode(1) . "&msg="  . urlencode("la modifica dell'esame non è andata a buon fine");
            header("Location: " . $url_errore);
            exit;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <?php include("../lib/head.php"); ?>
<body>
    <?php 
        include("../lib/navbar.php"); 
        messaggi_errore();
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