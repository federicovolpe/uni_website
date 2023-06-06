<?php
    //include delle funzioni
    include("../lib/functions.php");
    session_start();
    unset($_SESSION['id']);
    
    print("id: ".$_SESSION['id']."<br>
    email: ".$_SESSION['email']."<br>
    password: ".$_SESSION['password']."<br>
    matricola: ".$_SESSION['matricola']."<br>
    nome: ".$_SESSION['nome']."<br>
    cognome: ".$_SESSION['cognome']."<br>
    corso frequentato: ".$_SESSION['corso_frequentato']."<br>");
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
        <?php messaggi_errore()?>
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
        

    <form action="../change_password.php" style="padding: 5%; justify-content: center; align-items: center; display: flex;" method="POST">
        <h4>vuoi cambiare password? </br></h4>
        <label for="password">password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" style="padding:2%;" class="btn btn-primary">Cambia</button>
    </form>
    <?php script_boostrap()?>
</body>
</html>

