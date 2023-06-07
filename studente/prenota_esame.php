<?php
    include_once("../lib/functions.php");
    //fetch degli esami disponibili per lo studente corrente
    session_start();
    $matricola = $_SESSION['matricola'];
    if(isset($_GET["esame"])){
        $esame = $_GET["esame"];
        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($db){
            
            $query = "INSERT INTO prenotazione (matricola, esame) VALUES ('$matricola', '$esame')";
            $result = $conn->query($query);
            if($result){
                print("prenotazione effettuata con successo");
            }else{
            print("prenotazione non effettuata");
            }
        }else{ //messaggio di errore di connessione al database
            $_POST['msg'] = "la connessione al database NON è andata a buon fine";
            $_POST['approved'] = 1;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php include_once("../lib/head.php"); ?>
<body>
    <?php include_once('../lib/navbar.php'); 
            messaggi_errore_post2();
    ?>
    
        <?php  print("<h2>esami a cui ti puoi prenotare</h2>");?>
    <div>
        <div class= "table-container">
        <table class="table-striped">
        <caption><?php print("corso: ". $corso_frequentato . " utente: " . $nome . " " . $cognome) ?></caption>
            <thead>
                <tr>
                    <th> Materia </th>
                    <th> Data </th>
                    <th> Iscrizione </th>
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