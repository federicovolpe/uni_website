<h3>carriera valida dello studente:</h3>
<?php
    //file che consentirà la stampa in una tabella delle informazioni riguardanti la carriera valida di uno studente
    // dove carriera valida intende gli ultimi voti sufficienti ottenuti per ogni insegnamento

?>

<div>
        <div class= "table-container">
        <table class="table table-striped">
        <caption><?php print("corso: ". $_SESSION['corso_frequentato'] . " utente: " . $_SESSION['nome'] . " " . $_SESSION['cognome']) ?></caption>
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
                    $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        
        if($db){
            //tabella dove ci sono tutti gli esiti degli esami dello studente
            $esiti_sql = "SELECT I.nome, ES.data, E.esito
                            FROM esiti AS E
                            INNER JOIN esami AS ES ON E.esame = ES.id
                            INNER JOIN insegnamento AS I ON ES.insegnamento = I.id
                            WHERE E.studente = $1 AND 
                            GROUP BY E.esame, I.nome, ES.data, E.esito";
           
            $preparato1 = pg_prepare($db, "esiti", $esiti_sql);

            if($preparato1){
                $esiti = pg_execute($db, "esiti", array($matricola));
                if(pg_num_rows($esiti) > 0){
                    while($row = pg_fetch_assoc($esiti)){
                        echo '<tr>
                                <td>'. $row['nome'] .'</td>
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
                }else{
                    print("matricola non pervenuta");
                }
            ?>
        </table>
    </div>

SELECT E.insegnamento,esiti.esito,E.data
FROM esiti
JOIN esami AS E ON E.id = esiti.esame
WHERE studente = '966031'
GROUP BY E.insegnamento, esiti.esito;

101018
010122