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
    include_once('../lib/navbar.php');
?>
<body>
    <nav class="navbar bg-body-tertiary">
        <?php include_once('navbar.php')?>
    </nav>
        <?php messaggi_errore_post2()?>
        <?php  print("<h1>Benvenuto ".  $_SESSION['nome']." ". $_SESSION['cognome'] ."</h1>");?>
    <div>
        questa è la homepage dello studente<br>
        ecco i tuoi voti :

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
                <?php
                    display_esami_tablella($matricola);
                ?>
        </table>
    </div>
        

    <?php include_once('../lib/cambio_password.php')?>
    <?php script_boostrap()?>
</body>
</html>

