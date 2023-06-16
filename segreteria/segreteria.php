<?php
    //include delle funzioni
    include("../lib/functions.php");
    session_start();
    
    //se il parametro in get update_docente sono settati
    if(isset($_GET['update_docente'])){
        //chiamo la funzione update_docente
        include_once('funzioni_segreteria/update_docente.php');

    }if(isset($_GET['update_studente'])){
        //chiamo la funzione update_studente
        include_once('funzioni_segreteria/update_studente.php');

    }if(isset($_GET['update_corso'])){
        //chiamo la funzione update_corso
        include_once('funzioni_segreteria/update_corso.php');

    }// non c'è bisogno di fare anche per update_insegnamento perchè è già stato fatto un redirect nel form

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
    include_once("../lib/head.php"); 
    include_once("../lib/variabili_sessione.php");
    include_once('../lib/navbar.php');

?>
    <script>
        // script che fa in modo che quando si clicca il pulsante indietro si venga riportati a login.php
        //if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
          //  window.location.href = '../login.php';
        //}
    </script>
</head>

<body>
    <nav class="navbar bg-body-tertiary">
        <?php include_once('../lib/navbar.php')?>
    </nav>
    <?php //stampa di eventuali messaggi di errore
        messaggi_errore_post2();
        //stampa degli eventuali messaggi in $_SESSION
        if(isset($_SESSION['approved']) && $_SESSION['approved'] == 0 && isset($_SESSION['msg'])){
            echo '<div class="alert alert-success" role="alert">
            ' . $_SESSION['msg'] . '</div>';
            
        //altrimenti se la variabile approved è settata a 1, e c'è un messaggio di errore da mostrare
        }else if(isset($_SESSION['approved']) && $_SESSION['approved'] == 1 && isset($_SESSION['msg'])){
            echo '<div class="alert alert-danger" role="alert">
            ' . $_SESSION['msg'] . '</div>';
        }
        unset($_SESSION['msg']); 
        unset($_SESSION['approved']); 
    ?>

        <div class="row">
            <div class="col-sm-6">
                <h3>modifica o aggiungi un docente</h3>
                <form class="form-segreteria" action="<?php echo $_SERVER['PHP_SELF']; ?>?update_docente" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Id:</span>
                                        <input type="text" name="id" id="id" pattern="[0-9]{6}" title="Please enter exactly 6 numeric characters" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Email:</span>
                                        <input type="text" class="form-control" name="email" id="email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Nome:</span>
                                        <input type="text" class="form-control" name="nome" id="nome">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Cognome:</span>
                                        <input type="text" class="form-control" name="cognome" id="cognome">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                <div class="input-group mb-3">
                                        <span class = "input-group-text">Password:</span>
                                        <input type="text" class="form-control" name="password" id="password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                            <option value="aggiungi">Aggiungi</option>
                            <option value="modifica">Modifica</option>
                            <option value="cancella">Cancella</option>
                        </select>
                    </div>

                    <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
                </form>
            </div>

            <div class="col-sm-6">
                <h3>Modifica o aggiungi uno studente</h3>
                <form class="form-segreteria" action="<?php echo $_SERVER['PHP_SELF']; ?>?update_studente" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                <div class="input-group mb-3">
                                        <span class = "input-group-text">Matricola:</span>
                                        <input type="text" class="form-control" name="matricola" id="matricola" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Email:</span>
                                        <input type="text" class="form-control" name="email" id="email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Nome:</span>
                                        <input type="text" class="form-control" name="nome" id="nome">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Cognome:</span>
                                        <input type="text" class="form-control" name="cognome" id="cognome">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                <div class="input-group mb-3">
                                        <span class = "input-group-text">Password:</span>
                                        <input type="text" class="form-control" name="password" id="password">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Corso:</span>
                                            <select class="form-select" name="corso" id="corso" aria-label="Default select example">
                                                <!--opzioni fra i corsi disponibili -->
                                                <?php
                                                    $conn = pg_connect("host=localhost port=5432 dbname=unimio");
                                                    if($conn){
                                                        $sql = "SELECT *
                                                                FROM corso;";
                                                        $preparato = pg_prepare($conn, "corsi_disponibili", $sql);
                                                        if($preparato){
                                                            $eseguito = pg_execute($conn, "corsi_disponibili",array());
                                                            if (pg_num_rows($eseguito) >= 1) {
                                                                print('array: ' . pg_num_rows($eseguito) . '<br>');
                                                                while ($row = pg_fetch_assoc($eseguito)) {
                                                                    echo ("<option value='" . $row['id'] . "'>" . $row['nome_corso'] . " / " . $row['id'] . "</option>");
                                                                }
                                                            } else {
                                                                print("corsi non trovati");
                                                            }
                                                            
                                                        }
                                                    }else{
                                                        echo("connessione col database marcita");
                                                    }
                                                ?>
                                            </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                            <option value="aggiungi">Aggiungi</option>
                            <option value="modifica">Modifica</option>
                            <option value="cancella">Cancella</option>
                        </select>
                    </div>

                    <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-6">
                <h3>inserisci o modifica un corso</h3>
                <form class="form-segreteria" action="<?php echo $_SERVER['PHP_SELF']; ?>?update_corso" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Id:</span>
                                        <input type="text" class="form-control" name="id_corso" id="id_corso">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Nome:</span>
                                        <input type="text" class="form-control" name="nome_corso" id="nome_corso">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                  <label for="descrizione_corso">Descrizione:</label>
                  <textarea class="form-control" name="descrizione_corso" id="descrizione_corso" rows="4"></textarea>
                </div>
                <div class="col">
                  <label for="docente_responsabile">Docente responsabile:</label>
                  <select class="form-select" name="docente_responsabile" id="docente_responsabile" aria-label="Default select example">
                    <?php //generazione della selezione dei corsi
                      $db = pg_connect("host=localhost port=5432 dbname=unimio ");
                      $sql = "SELECT nome, cognome, id FROM docente";
                      $result = pg_query($db, $sql);
                      while ($row = pg_fetch_assoc($result)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] ." ".$row['cognome']." / " .$row['id']."</option>";
                      }
                    ?>
                  </select>
                </div>
                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="form-row">
                                        <select class="form-select" name="laurea" id="laurea" aria-label="Default select example">
                                            <option value="triennale">Triennale</option>
                                            <option value="magistrale">Magistrale</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                            <option value="aggiungi">Aggiungi</option>
                            <option value="modifica">Modifica</option>
                            <option value="cancella">Cancella</option>
                        </select>
                    </div>

                    <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
                </form>
            </div>

            <div class="col-sm-6">
    <form class="form-segreteria" action="funzioni_segreteria/update_insegnamento.php" method="POST">
      <h3>Inserisci o modifica un insegnamento</h3>
      <div class="form-row">
        <div class="form-group">
          <div class="row">
            <div class="col">
              <div class="input-group mb-3">
                <span class="input-group-text">id:</span>
                <input type="text" class="form-control" name="id_insegnamento" id="id_insegnamento" required>
              </div>
            </div>
            <div class="col">
              <div class="input-group mb-3">
                <span class="input-group-text">Nome:</span>
                <input type="text" class="form-control" name="nome" id="nome">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <div class="row">
            <div class="col">
              <div class="form-row align-items-center">
                <div class="col">
                  <select class="form-select" name="anno" id="anno" aria-label="Default select example">
                  <option value="1">Primo</option>
                    <option value="2">Secondo</option>
                    <option value="3">Terzo</option>
                  </select>
                </div>
                <div class="col">
                  <label for="cfu-input">CFU:</label>
                  <input type="number" name="cfu" min="1" max="15" step="1">
                </div>
                <div class="col">
                  <label for="descrizione-input">Descrizione:</label>
                  <textarea class="form-control" name="descrizione" id="descrizione" rows="4"></textarea>
                </div>
                <div class="col">
                  <label for="docente_responsabile">Docente responsabile:</label>
                  <select class="form-select" name="insegnante_responsabile" id="insegnante_responsabile" aria-label="Default select example">
                    <?php //generazione della selezione dei corsi
                      $db = pg_connect("host=localhost port=5432 dbname=unimio ");
                      $sql = "SELECT nome, cognome, id FROM docente";
                      $result = pg_query($db, $sql);
                      while ($row = pg_fetch_assoc($result)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] ." ".$row['cognome']." / " .$row['id']."</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="col">
                  <label for="corso-input">Corso:</label>
                  <select class="form-select" name="corso" id="corso" aria-label="Default select example">
                    <?php //generazione della selezione dei corsi
                      $db = pg_connect("host=localhost port=5432 dbname=unimio ");
                      $sql = "SELECT nome_corso as nome, id FROM corso";
                      $result = pg_query($db, $sql);
                      while ($row = pg_fetch_assoc($result)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


                    <div class="form-row">
                        <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                            <option value="aggiungi">Aggiungi</option>
                            <option value="modifica">Modifica</option>
                            <option value="cancella">Cancella</option>
                        </select>
                    </div>

                    <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
                </form>
            </div>
        </div>

        controlla la carriera di uno studente
        <form action="carriera_studente.php" method="POST">

            <span class = "input-group-text">Matricola:</span>
            <input type="text" class="form-control" name="matricola" id="matricola" required>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active">
                    <input type="radio" name="tipo_carriera" value="carriera_completa" id="valida" autocomplete="off" checked> carriera completa
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="tipo_carriera" value="carriera_valida" id="option2" autocomplete="off"> carriera valida
                </label>
            </div>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active">
                    <input type="radio" name="storico_studente" value="studente_passato" id="valida" autocomplete="off" checked> studente passato
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="storico_studente" value="studente_attuale" id="option2" autocomplete="off"> studente attuale
                </label>
            </div>
            <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
        </form>

        <?php include_once('../lib/cambio_password.php')?>
    </div>


</body>
    <?php script_boostrap()?>
</html>