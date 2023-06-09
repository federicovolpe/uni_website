<!-- script per la stampa della carriera valida di uno studente in forma tabellare -->

<h3>carriera valida dello studente:</h3>

<div>
    <div class="table-container">
        <table class="table table-striped">
            <?php 
                // recupero del nome del corso
                $db = pg_connect("host = localhost port = 5432 dbname = unimio");
                $sql = "SELECT nome_corso 
                        FROM corso 
                        WHERE id = $1";

                $nome_corso = pg_query_params($db, $sql, array($_SESSION['corso_frequentato']));
                $array = pg_fetch_assoc($nome_corso);
                $nome_corso = $array['nome_corso'];
            ?>

            <caption><?php print("corso: " . $nome_corso . "       utente: " . $_SESSION['nome'] . " " . $_SESSION['cognome']) ?></caption>
            <thead>
                <tr>
                    <th scope="col"> Materia </th>
                    <th scope="col"> Data </th>
                    <th scope="col"> Docente </th>
                    <th scope="col"> Voto </th>
                </tr>
            </thead>
                <?php
                    $matricola = $_SESSION['matricola'];  //recupero della matricola per la query
                    if ($db) {
                        //tabella dove ci sono tutti gli esiti degli esami dello studente
                        $esiti_sql = "SELECT I.nome AS insegnamento, esiti.esito, E.data, D.nome AS docente_nome, D.cognome                         
                                        FROM esiti                                                                      
                                        JOIN esami AS E ON E.id = esiti.esame 
                                        JOIN insegnamento AS I on I.id = E.insegnamento  
                                        JOIN docente as D ON I.responsabile = D.id                                       
                                        WHERE studente = $1 AND esiti.esito >= 18                                 
                                                AND E.data = (SELECT MAX(data)                                                                
                                                                FROM esami                                                                      
                                                                WHERE esami.insegnamento = E.insegnamento)";

                        $preparato1 = pg_prepare($db, "esiti", $esiti_sql);

                        if ($preparato1) {
                            $esiti = pg_execute($db, "esiti", array($matricola));
                            if (pg_num_rows($esiti) > 0) {
                                while ($row = pg_fetch_assoc($esiti)) {
                                    echo '<tr>
                                                <td>' . $row['insegnamento'] . '</td>
                                                <td>' . $row['data'] . '</td>
                                                <td>' . $row['docente_nome'].' '. $row['cognome'] . '</td>';
                                    if ($row['esito'] < 18) {
                                        echo '<td style="color: red;">' . $row['esito'] . '</td>';
                                    } else {
                                        echo '<td style="color: green;">' . $row['esito'] . '</td>';
                                    }
                                }
                            } else {
                                $_POST['msg'] = 'non ci sono esiti registrati per lo studente ' . $matricola;
                                $_POST['approved'] = 1;
                            }
                        } else {
                            $_POST['msg'] = pg_last_error();
                            $_POST['approved'] = 1;
                        }
                    } else {
                        $_POST['msg'] = 'connessione al database non riuscita';
                        $_POST['approved'] = 1;
                    }
                ?>
        </table>
    </div>