<!--  pagina per la consultazione dei voti ricevuti dallo studente   -->

<?php
    include_once("../lib/functions.php");
    //fetch degli esami disponibili per lo studente corrente
    session_start();
    $matricola = $_SESSION['matricola'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("../lib/head.php"); ?>
    <script>
        // script che fa in modo che quando si clicca il pulsante indietro si venga riportati a login.php
        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
            window.location.href = '../login.php';
        }
    </script>
</head>

<body>
    <?php include_once('../lib/navbar.php'); ?>
    
        
    <div class="row" style="margin-top: 3%;margin: 5%;">
        <h2>tutti gli esiti degli esami sostenuti</h2>
        <div class= "table-container" >
        
        <table class="table table-striped">
            <?php // recupero del nome del corso
                $db = pg_connect("host = localhost port = 5432 dbname = unimio");
                $sql = "SELECT nome_corso 
                        FROM corso 
                        WHERE id = $1";
                        
                $nome_corso = pg_query_params($db, $sql, array($_SESSION['corso_frequentato']));
                $array = pg_fetch_assoc($nome_corso);
                $nome_corso = $array['nome_corso'];
            ?>    
        <caption><?php print("corso: ". $nome_corso . ",  -------  utente: " . $_SESSION['nome'] . " " . $_SESSION['cognome']) ?></caption>
            <thead>
                <tr>
                    <th scope="col"> Materia </th>
                    <th scope="col"> Data </th>
                    <th scope="col"> Voto </th>
                </tr>
            </thead>
                <?php
                    if(!empty($matricola)){
                        //query per ottenere tutti gli esami a cui lo studente si può iscrivere
                        display_esiti_esami($matricola);
                    }else{
                        print("matricola non pervenuta");
                    }
                ?>
        </table>
    </div>
        
    
</body>
    <?php script_boostrap() ?>
</html>