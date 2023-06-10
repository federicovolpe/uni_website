<?php
    //include delle funzioni
    session_start();
    include("../../lib/functions.php");
    
    $_SESSION['esame_id']  = $_GET['esame'];
    print'esame id: '.$_SESSION['esame_id'];
    //se è stato premuto il tasto submit allora la variabile chaange è settata a 1
    if($_GET['change'] == 1){
        //obiettivo: modificare la data dell'esame
        //recupero delle variabili settate in post per la modifica
        $esame_id = $_POST['esame']; 
        $newDate = $_POST['new_date'];
        $nuova_data_formattata = date_format(date_create($newDate), 'dmy');
        
        $db = pg_connect("dbname=unimio host=localhost port=5432");
        if($db){
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
    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php include("../../lib/head.php"); ?>
<body>
    <?php 
        include("../../lib/navbar.php"); 
        messaggi_errore_post2();
        //quando viene aperta la pagina passo la variabile id esame fra le variabili $session per quendo verà effettuata la query di modifica
        $_SESSION['esame_id'] = $_GET['esame'];
    ?>
    
    <h1>Update Esame</h1>

    esame da modificare: 
        <?php 
            echo $_GET['esame']; 
            $db = pg_connect("dbname=unimio host=localhost port=5432");
            $info_esame_sql = "SELECT E.id, I.nome AS i_nome, D.nome AS d_nome, E.data
                               FROM esami AS E
                               JOIN insegnamento AS I ON E.insegnamento = I.id
                               JOIN docente AS D ON E.docente = D.id 
                               WHERE E.id = $1";
            pg_prepare($db, "info_esame", $info_esame_sql);
            $info_esame = pg_execute($db, "info_esame", array($_GET['esame']));
            $row = pg_fetch_assoc($info_esame);
            echo "<br>data: " . $row['data'] . "
                  <br>insegnamento: " . $row['i_nome']."
                  <br>docente: " . $row['d_nome']."
                  <br>id esame: " . $row['id'];
        ?>

    <!--                                   form per la raccolta di info per la modifica dell'esame                               -->
        <form method="POST" action="update_esame.php?change=1">
            <label for="new_date">New Date:</label>
            <input type="date" id="new_date" name="new_date" required>
            <!--passaggio dell'id esame tramite post-->
            <input type="hidden" name="esame" value="<?php echo $_SESSION['esame_id']; ?>">
            <input type="submit" value="Update">
        </form>
    </body>
    <?php script_boostrap()?>
</html>