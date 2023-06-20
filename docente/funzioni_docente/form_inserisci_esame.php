<!-- form per la raccolta dei dati per l'inserimento di un esame nuovo da parte del docente -->

<form style="border: 1px solid blue;
    padding: 4%;
    width: 90%;
    border-radius: 3%;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 auto;"

    action="<?php echo $_SERVER['PHP_SELF']; ?>?inserisci_esame" method="POST">
    <h3 style="text-align: center;">programma un nuovo esame:</h3>

    <div class="form-row" style="text-align: center;">
        <div class="form-group">
            <div class="row">

                <div class="col">
                    <select class="form-select" name="id_insegnamento" id="id_insegnamento" aria-label="Default select example">
                        <!--opzioni fra gli insegnamenti del professore specificato-->
                        <?php
                            $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
                            if($conn){ 
                                //query che per il docente selezionato raccoglie tutti gli insegnamenti di cui è responsabile
                                $sql = "SELECT I.nome AS nome_insegnamento, id as id_insegnamento
                                        FROM insegnamento as I 
                                        WHERE responsabile = $1;";
                                        
                                $result = pg_prepare($conn, "insegnamenti_responsabile", $sql);
                                $insegnamenti = pg_execute($conn, "insegnamenti_responsabile", array($_SESSION['id']));
                                print("query eseguita</br>");
                                if(pg_num_rows($insegnamenti) >= 1){
                                    print("insegnamenti trovati!");
                                    
                                    while($row = pg_fetch_assoc($insegnamenti)){
                                        echo("<option value='" . $row['id_insegnamento'] . "'>".$row['nome_insegnamento']." / ".$row['id_insegnamento'] ."</option>");
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
                    <label for="data">Data esame:</label>
                        <input type="date" id="data" name="data" required>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <button type="submit" style="padding:3px;" class="btn btn-primary">Inserisci</button>
</form>