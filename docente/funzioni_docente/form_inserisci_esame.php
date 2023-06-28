<!-- form per la raccolta dei dati per l'inserimento di un esame nuovo da parte del docente -->
<div style="display: flex; align-items: center; justify-content: center; margin-bottom: 2%">
    <div class="form-segreteria" style="display: flex; flex-direction: column; align-items: center; width: 90%; text-align: center;">

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?inserisci_esame" method="POST">
    <h3>programma un nuovo esame:</h3>
    <hr></hr>
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

                                if(pg_num_rows($insegnamenti) >= 1){
                                    
                                    while($row = pg_fetch_assoc($insegnamenti)){
                                        echo("<option value='" . $row['id_insegnamento'] . "'>".$row['nome_insegnamento']." / ".$row['id_insegnamento'] ."</option>");
                                    }

                                }else{
                                    echo("insegnamenti non trovati");
                                }
                            }else{
                                echo("connessione col database fallita");
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
</div>
    </div>
