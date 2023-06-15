<?php
    include_once("../lib/functions.php");
    //fetch degli esami disponibili per lo studente corrente
    session_start();
    $matricola = $_POST['matricola'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("../lib/head.php"); ?>
    <script>
        // script che fa in modo che quando si clicca il pulsante indietro si venga riportati a login.php
        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
            window.location.href = 'segreteria.php';
        }
    </script>
</head>

<body>
    <?php include_once('../lib/navbar.php'); 
            include_once('../lib/variabili_sessione.php');
            
    ?>
    
    <?php if($_POST['tipo_carriera'] == 'carriera_completa'){ // stampa di una carriera completa di uno studente?>
        <h2>tutti gli esiti degli esami sostenuti</h2>
    <div>
        <div class= "table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"> Materia </th>
                    <th scope="col"> Data </th>
                    <th scope="col"> Voto </th>
                </tr>
            </thead>
        <?php 
            if(!empty($matricola)){
                if($_POST['storico_studente'] == 'studente_attuale'){ //carriera di uno studente attuale 
                    
                    //query per ottenere tutti gli esami a cui lo studente si può iscrivere
                    display_esiti_esami($matricola);
                        
                }else if($_POST['storico_studente'] == 'studente_passato'){ //stampa della carriera completa di uno studente passato
                        
                    $db = pg_connect("host = localhost port = 5432 dbname = unimio");
                    if($db){    //tabella dove ci sono tutti gli esiti degli esami dello studente
                        
                        $esiti_sql = "SELECT I.nome AS insegmamento, ES.data, E.esito
                                        FROM storico_carriera AS E
                                        INNER JOIN esami AS ES ON E.esame = ES.id
                                        INNER JOIN insegnamento AS I ON ES.insegnamento = I.id
                                        WHERE E.studente = $1
                                        GROUP BY E.esame, I.nome, ES.data, E.esito";
                    
                        $preparato1 = pg_prepare($db, "esiti", $esiti_sql);

                        if($preparato1){
                            $esiti = pg_execute($db, "esiti", array($matricola));
                            if(pg_num_rows($esiti) > 0){
                                while($row = pg_fetch_assoc($esiti)){
                                    echo '<tr>
                                            <td>'. $row['insegnamento'] .'</td>
                                            <td>'. $row['data'] .'</td>';
                                    if( $row['esito'] < 18){
                                        echo '<td style="color: red;">'. $row['esito'] .'</td>';
                                    }else{
                                        echo '<td style="color: green;">'. $row['esito'] .'</td>';
                                    }
                                }
                            }else{                
                                $_POST['msg'] = 'non ci sono esiti registrati per lo studente '.$matricola;
                                $_POST['approved'] = 1;
                            }
                        }else{                  
                            $_POST['msg'] = pg_last_error();
                            $_POST['approved'] = 1;
                        }
                    }else{                  
                        $_POST['msg'] = 'connessione al database non riuscita';
                        $_POST['approved'] = 1;
                    }
                }
            }
        ?>
        </table>
    </div>

    <!----------------------------------------- STAMPA DELLA CARRIERA VALIDA --------------------------------->
    <?php }else if($_POST['tipo_carriera'] == 'carriera_valida'){?>
        
        <h2>carriera valida dello studente</h2>
        <div>
        <div class= "table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"> Materia </th>
                    <th scope="col"> Data </th>
                    <th scope="col"> Voto </th>
                </tr>
            </thead>
            <?php 
            if($_POST['storico_studente'] == 'studente_attuale'){  //stampa di una carriera valida di uno studente attuale
                $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        
                if($db){
                    //tabella dove ci sono tutti gli esiti degli esami dello studente
                    $esiti_sql = "SELECT I.nome AS insegnamento, esiti.esito, E.data                             
                                FROM esiti                                                                      
                                JOIN esami AS E ON E.id = esiti.esame 
                                JOIN insegnamento AS I on I.id = E.insegnamento                                         
                                WHERE studente = $1 AND esiti.esito >= 18                                 
                                        AND E.data = (SELECT MAX(data)                                                                
                                                        FROM esami                                                                      
                                                        WHERE esami.insegnamento = E.insegnamento)";
                
                    $preparato1 = pg_prepare($db, "esiti", $esiti_sql);

                    if($preparato1){
                        $esiti = pg_execute($db, "esiti", array($matricola));
                        if(pg_num_rows($esiti) > 0){
                            while($row = pg_fetch_assoc($esiti)){
                                echo '<tr>
                                        <td>'. $row['insegnamento'] .'</td>
                                        <td>'. $row['data'] .'</td>';
                                if( $row['esito'] < 18){
                                    echo '<td style="color: red;">'. $row['esito'] .'</td>';
                                }else{
                                    echo '<td style="color: green;">'. $row['esito'] .'</td>';
                                }
                            }
                        }else{                
                            $_POST['msg'] = 'non ci sono esiti registrati per lo studente '.$matricola;
                            $_POST['approved'] = 1;
                        }
                    }else{                  
                        $_POST['msg'] = pg_last_error();
                        $_POST['approved'] = 1;
                    }
                }else{                  
                    $_POST['msg'] = 'connessione al database non riuscita';
                    $_POST['approved'] = 1;
                }
            } else if($_POST['storico_studente'] == 'studente_passato'){ //stampa della carriera valida di uno studente passato
                $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        
                if($db){
                    //tabella dove ci sono tutti gli esiti degli esami dello studente
                    $esiti_sql = "SELECT I.nome AS insegnamento, storico_carriera.esito, E.data                             
                    FROM storico_carriera                                                       
                    JOIN esami AS E ON E.id = storico_carriera.esame
                    JOIN insegnamento AS I on I.id = E.insegnamento                                         
                    WHERE studente = $1 AND storico_carriera.esito >= 18                                 
                            AND E.data = (SELECT MAX(data)                                                                
                                            FROM esami                                                                      
                                            WHERE esami.insegnamento = E.insegnamento)";
                
                    $preparato1 = pg_prepare($db, "esiti", $esiti_sql);

                    if($preparato1){
                        $esiti = pg_execute($db, "esiti", array($matricola));
                        if(pg_num_rows($esiti) > 0){
                            while($row = pg_fetch_assoc($esiti)){
                                echo '<tr>
                                        <td>'. $row['insegnamento'] .'</td>
                                        <td>'. $row['data'] .'</td>';
                                if( $row['esito'] < 18){
                                    echo '<td style="color: red;">'. $row['esito'] .'</td>';
                                }else{
                                    echo '<td style="color: green;">'. $row['esito'] .'</td>';
                                }
                            }
                        }else{             
                            print'nessun esito registrato per: '.$esiti_sql.' <br> e matricola: '.$matricol;     
                            $_POST['msg'] = 'non ci sono esiti registrati per lo studente '.$matricola;
                            $_POST['approved'] = 1;
                        }
                    }else{                  
                        $_POST['msg'] = pg_last_error();
                        $_POST['approved'] = 1;
                    }
                }else{                  
                    $_POST['msg'] = 'connessione al database non riuscita';
                    $_POST['approved'] = 1;
                }
            }

        ?>
        </table>
    </div>
    <?php } else { print'istruzione non trovata';
    //lettura dei messaggi messa alla fine perchè se no verrebbe eseguita prima della generazione dei messaggi
    }messaggi_errore_post2();?>    
    
</body>
    <?php script_boostrap() ?>
</html>