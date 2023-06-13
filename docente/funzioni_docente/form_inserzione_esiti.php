
<!-- form per l'inserzione di un voto di uno studente da parte del docente-->

<div style="display: flex; justify-content: center; height: 10vh;">
    <div style="border: 2px solid blue; padding: 20px;">
        <div style="display: grid; gap: 10px; justify-items: center; text-align: center;">
            <h3>inserzione esito</h3>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?inserisci_esiti" method="POST">
                <div style="padding: 2%;">
                    <label for="m_studente">Matricola studente:</label>
                    <input type="text" name="m_studente" id="m_studente">
                </div>

                <div style="padding: 2%;">
                    <label for="esame">Esame:</label>
                    <select class="form-select" name="insegnamento_n" id="insegnamento_n" aria-label="Default select example">
                        <?php
                            $conn = pg_connect("host=localhost port=5432 dbname=unimio");
                            if($conn){
                                $sql = "SELECT insegnamento.nome as insegnamento_n, data, esami.id as esami_id
                                        FROM esami 
                                        JOIN insegnamento ON insegnamento.id = esami.insegnamento
                                        WHERE docente = $1";
                                $result = pg_prepare($conn, "esami_in_programma", $sql);
                                $insegnamenti = pg_execute($conn, "esami_in_programma", array($_SESSION['id']));
                                if(pg_num_rows($insegnamenti) >= 1){
                                    while($row = pg_fetch_assoc($insegnamenti)){
                                        echo("<option value='" . $row['insegnamento_n'] ."'>" . $row['insegnamento_n'] . " / " . $row['data'] . "</option>");
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

                <div style="padding: 2%;">
                    <label for="esito">Esito:</label>
                    <input type="number" name="esito" id="esito" min="0" max="30">
                </div>

                <div style="padding: 2%;">
                    <button type="submit" class="btn btn-primary" style="padding: 2%;">Esegui</button>
                </div>
            </form>
        </div>
    </div>
</div>
