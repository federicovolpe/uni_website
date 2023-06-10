<?php
    session_start();
    //include delle funzioni
    include_once("../lib/functions.php");
    unset($_SESSION['id']);
    include_once('../lib/variabili_sessione.php');

    //se la pagina è stata con una oprazione di cambio password
    if(isset($_GET['change_password'])){
        //utilizzo la funzione change password
        include_once('../change_password.php');
    }

    //se lo studente intende fare la rinuncia agli studi
    if(isset($_GET['rinuncia_agli_studi'])){
        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($db){
            $rinuncia_sql = "DELETE FROM studente WHERE matricola = $1";
            $preparato = pg_prepare($db, 'rinuncia', $rinuncia_sql);
            $result = pg_execute($db, 'rinuncia', array($_SESSION['matricola']));
            if($result){
                $_POST['msg'] = "Rinuncia avvenuta con successo";
                $_POST['approved'] = 0;
            }else{
                $_POST['msg'] = "Rinuncia fallita";
                $_POST['approved'] = 1;
            }
        }else{
            $_POST['msg'] = "connessione al database fallita";
            $_POST['approved'] = 1;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php
    include_once("../lib/head.php"); 
?>
<body>
    <?php
        include_once('../lib/navbar.php');
    ?>
        <?php messaggi_errore_post2()?>
        <?php  print("<h1>Benvenuto ".  $_SESSION['nome']." ". $_SESSION['cognome'] ."</h1>");?>
    <div style="text-align: center;">
        questa è la homepage dello studente<br>
        dati dell'utente:<br>
        <table>
            stampa dei dati dell'utente
        </table>

        ti vuoi prenotare per un esame?<br>
        <a href="prenota_esame.php">prenota un esame</a><br>

        <br>vuoi visualizzare i tuoi voti?<br>
        <a href="esiti_esami.php">esiti esami</a><br>

        <br>stampa la tua carriera<br>
        <a href="esiti_esami.php">esiti esami</a><br>
    </div>
        
        <?php include_once('../lib/cambio_password.php')?>
        <?php script_boostrap()?>

    
        <div class="rinuncia_studi" style="position: fixed; bottom: 10px; right: 10px;">
    <h4>Vuoi Rinunciare agli studi?</h4>
    <form class="c-password" action="<?php echo $_SERVER['PHP_SELF']; ?>?rinuncia_agli_studi" method="POST">
        <button type="submit" class="btn btn-primary">Rinuncia</button>
    </form>
</div>

</body>
</html>

