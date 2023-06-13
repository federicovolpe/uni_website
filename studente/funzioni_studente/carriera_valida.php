<h3>carriera valida dello studente:</h3>

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
            $matricola = $_SESSION['matricola'];  //recupero della matricola per la query
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
            ?>
        </table>
    </div>
