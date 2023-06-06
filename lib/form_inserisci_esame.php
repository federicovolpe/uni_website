<form class="form-segreteria" method="POST">
    <div class="form-row">
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <select class="form-select" name="insegnamento" id="insegnamento" aria-label="Default select example">
                        <!--opzioni fra gli insegnamenti del professore specificato-->
                        <?php
                            $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
                            if($conn){
                                $sql = "SELECT R.docente, I.nome AS nome_insegnamento
                                        FROM responsabile_insegnamento AS R
                                        JOIN insegnamento AS I ON I.id = R.insegnamento
                                        WHERE docente = $1";
                                print("query settata</br>");
                                $result = pg_prepare($conn, "insegnamenti_responsabile", $sql);
                                $insegnamenti = pg_execute($conn, "insegnamenti_responsabile", array($_SESSION['id']));
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
                </div>
                <div class="col">
                    <div class="input-group mb-3">
                    <label for="date">Data esame:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <button type="submit" style="padding:2%;" class="btn btn-primary">Inserisci</button>
</form>