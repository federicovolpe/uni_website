
                                            </select>
                                            <select class="form-select" name="insegnamento" id="insegnamento" aria-label="Default select example">
                        <!--opzioni fra gli insegnamenti del professore specificato-->
                        <?php
                            $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
                            if($conn){
                                $sql = "SELECT R.docente, I.nome AS nome_insegnamento
                                        FROM responsabile_insegnamento AS R
                                        JOIN insegnamento AS I ON I.id = R.insegnamento
                                        WHERE docente = 54198";
                                print'sql : '.$sql.'<br>';
                                $result = pg_prepare($conn, "insegnamenti_responsabile", $sql);
                                $insegnamenti = pg_execute($conn, "insegnamenti_responsabile");
                                print("query eseguita</br>");
                                if(pg_num_rows($insegnamenti) >= 1){
                                    print("insegnamenti trovati!");
                                    
                                    while($row = pg_fetch_assoc($insegnamenti)){
                                        echo("<option value='" . $row['nome_insegnamento'] . "'>".$row['nome_insegnamento']."</option>");
                                    }
                                }else{
                                    echo("insegnamenti non trovati");
                                }
                            }else{
                                echo("connessione col database marcita");
                            }
                        ?>
                    </select>