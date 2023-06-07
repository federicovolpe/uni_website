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
    <div>
        questa è la homepage dello studente<br>
        dati dell'utente:<br>
        <table>
            stampa dei dati dell'utente
        </table>

        ti vuoi prenotare per un esame?<br>
        <a href="prenota_esame.php">prenota un esame</a>

        vuoi visualizzare i tuoi voti?<br>
        <a href="esiti_esami.php">esiti esami</a>
        <div>corso frequentato: <?php print($corso_frequentato) ?></div>
    </div>    

    <?php include_once('../lib/cambio_password.php')?>
    <?php script_boostrap()?>
</body>
</html>

