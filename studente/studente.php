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
        ti vuoi prenotare per un esame?
    <a href="prenota_esame.php">prenota un esame</a>

    vuoi visualizzare i tuoi voti?
    <a href="esiti_esami.php">esiti esami</a>
        <!-- inizio tabella -->
    <div>corso frequentato: <?php print($corso_frequentato) ?></div>
        <div class= "table-container">
        <table class="table-striped">
        
            <thead>
                <tr>
                    <th> Materia </th>
                    <th> Voto </th>
                    <th> Data </th>
                    <th> Iscrizione</th>
                </tr>
            </thead>
        </table>
    </div>
        

    <?php include_once('../lib/cambio_password.php')?>
    <?php script_boostrap()?>
</body>
</html>

