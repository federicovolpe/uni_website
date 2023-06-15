
<?php
    $db = pg_connect("host = localhost port = 5432 dbname = unimio");

    //control
    if($db){
        switch ($_POST['operazione']){
            case 'aggiungi':
                    //verifica che non esistano già insegnamenti con lo stesso id
                    $check_sql = "SELECT 1
                        FROM insegnamento
                        WHERE id = $1";
                    $result = pg_prepare($db, "check", $check_sql);
                    $result = pg_execute($db, "check", array($_POST['id']));
                    if($result){//ritorno un messaggio di errore
                        $_POST['approved'] = 1;
                        $_POST['msg'] = "Esiste già un insegnamento con questo id";
                    }else{
                        $insertion_sql = "INSERT INTO insegnamento (id, nome, cfu, anno, semestre, corso_di_laurea) 
                            VALUES ($1, $2, $3, $4, $5, $6)";
                        $preparato = pg_prepare($db, "inserzione", $insertion_sql);
                        if($preparato){
                            $inserito = pg_execute($db, "inserzione", array($_POST['id'], $_POST['nome'], $_POST['cfu'], $_POST['anno'], $_POST['semestre'], $_POST['corso_di_laurea']));
                            if($inserito){
                                $_POST['approved'] = 0;
                                $_POST['msg'] = "L'insegnamento è stato inserito con successo";
                            }else{
                                $_POST['approved'] = 1;
                                $_POST['msg'] = pg_last_error();
                            }
                        }else{
                            $_POST['approved'] = 1;
                            $_POST['msg'] = pg_last_error();
                        }
                    }
                
                break;
            case 'modifica':$sql = "SELECT R.docente, I.nome AS nome_insegnamento
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
                break;
            case 'elimina':
                break;

        }
    }else{
        $_POST['msg'] = "Errore di connessione al database";
        $_POST['approved'] = 1;
    }
?>

<!--codice html per la scelta dei corsi propedeutici-->

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <select>
        <?php
            $db = pg_connect("host=localhost port=5432 dbname=unimio ");
            $sql = "SELECT nome, id FROM insegnamento WHERE corso = $1";
            $result = pg_query_params($db, $sql, array($_POST['corso']));
            print'numero di righe: '.pg_num_rows($result);
            while ($row = pg_fetch_assoc($result)) {
                echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
            }
        ?>
    </select>
</form>