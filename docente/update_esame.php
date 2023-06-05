<?php
    //include delle funzioni
    include("../lib/functions.php");

//obiettivo: modificare la data dell'esame
    $esame_id = $_GET['esame'];
    $newDate = $_POST['new_date'];
    print("esame : ". $esame_id."</br>");
$nuova_data_formattata = date_format(date_create($newDate), 'dmy');
    print("data : ". $formattedDate."</br>");
    
    $db = pg_connect("dbname=unimio host=localhost port=5432");
        $sql = "UPDATE esami SET data = $1 WHERE id = $2";
        $preparazione = pg_prepare($db , "update", $sql);

        if($preparazione){
            pg_execute($db, "update", array($nuova_data_formattata,$esame_id));
            print("update dell'esame");

            $url_errore ="update_esame.php?approved=" . urlencode(0) ;
            //header("Location: " . $url_errore);
            //exit;
        }else{
            print("qualcosa è andato storto nella preparazione della query");
            $url_errore ="update_esame.php?approved=" . urlencode(1) . "&msg="  . urlencode("la modifica dell'esame non è andata a buon fine");
            header("Location: " . $url_errore);
            exit;
        }

?>
<!DOCTYPE html>
<html lang="en">
<?php include("head.php"); ?>
<body>
<nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../immagini/logo_unimi.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> Università degli Studi di Milano
            </a>
        </div>
    </nav>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const approved = urlParams.get('approved');
        const msg = urlParams.get('msg');
        <?php messaggi_errore()?>
    </script>
    <h1>Update Esame</h1>
        <form method="POST" action="">
            <label for="new_date">New Date:</label>
            <input type="date" id="new_date" name="new_date" required>
            <input type="submit" value="Update">
        </form>
    </body>
    <?php script_boostrap()?>
</html>