<!-- script per la prenotazione di un esame da parte dello studente -->

<?php
    include_once("../lib/functions.php");
    //fetch degli esami disponibili per lo studente corrente
    session_start();
    $matricola = $_SESSION['matricola'];

    //se il parametro get esame è settato allora è stata richiesta una iscrizione al suddetto esame
    if(isset($_GET["esame"])){
        $esame = $_GET["esame"];

        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($db){
            
            //query per inserire la prenotazione
            $sql = "INSERT INTO iscrizioni (studente, esame) 
                    VALUES ($1, $2)";

            $preparato = pg_prepare($db, "iscrivi", $sql);
            $result = pg_execute($db, "iscrivi", array($matricola, $esame));

            if($result){ //se la query di inserimento va a buon fine
                $_POST['msg'] = "l'iscrizione è andata a buon fine";
                $_POST['approved'] = 0;

            }else{
                $_POST['msg'] = pg_last_error();
                $_POST['approved'] = 1;
            }
        }else{ //messaggio di errore di connessione al database
            $_POST['msg'] = "la connessione al database NON è andata a buon fine";
            $_POST['approved'] = 1;
        }
    }

    // se invece è stata impostata la cancellazione dell'iscrizione all'esame allora la cancello
    if(isset($_GET["c_esame"])){
        $esame = $_GET["c_esame"];

        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($db){
            
            //query per inserire la prenotazione
            $sql = "DELETE FROM iscrizioni 
                    WHERE studente = $1 AND esame = $2";

            $preparato = pg_prepare($db, "cancella", $sql);
            $result = pg_execute($db, "cancella", array($matricola, $esame));

            if($result){ //se la query di inserimento va a buon fine
                $_POST['msg'] = "la cancellazione dell'iscrizione è andata a buon fine";
                $_POST['approved'] = 0;

            }else{
                $_POST['msg'] = pg_last_error();
                $_POST['approved'] = 1;
            }
        }else{ //messaggio di errore di connessione al database
            $_POST['msg'] = "la connessione al database NON è andata a buon fine";
            $_POST['approved'] = 1;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("../lib/head.php"); ?>
</head>

<body style="background-color: white;">
    <?php include_once('../lib/navbar.php'); ?>
    
    <div style="margin-top: 3%;margin: 5%;">
    <h2>esami a cui ti puoi prenotare</h2>
        <div class= "table-container">
        <table class="table table-striped">
            <?php
                //query per il recupero del nome del corso
                $db = pg_connect("host = localhost port = 5432 dbname = unimio");
                $nome_corso_sql = "SELECT nome_corso 
                                    FROM corso
                                    WHERE id = $1";
                $nome_corso = pg_fetch_assoc(pg_query_params($db, $nome_corso_sql, array($_SESSION['corso_frequentato'])));
                $nome_c = $nome_corso['nome_corso']
            ?>
        <caption><?php print("corso: ". $nome_c . ",  -------  utente: " . $_SESSION['nome'] . " " . $_SESSION['cognome']) ?></caption>
            <thead>
                <tr>
                    <th scope="col"> Materia </th>
                    <th scope="col"> Data </th>
                    <th scope="col"> Docente </th>
                    <th scope="col"> Iscrizione </th>
                </tr>
            </thead>
            <?php
                if(!empty($matricola)){
                    //query per ottenere tutti gli esami a cui lo studente si può iscrivere
                    display_esami_prenotabili($matricola);
                    
                }else{
                    print("matricola non pervenuta");
                }
            ?>
        </table>
    </div>
        
    
</body>
    <?php script_boostrap() ?>
</html>