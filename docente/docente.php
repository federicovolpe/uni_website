
<?php
    //include delle funzioni
    include("../lib/functions.php");
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php
    include_once("../lib/head.php"); 
    include_once("../lib/variabili_sessione.php");
    include_once('../lib/navbar.php');
        messaggi_errore();
?>
<body>
<?php 
    include_once("../lib/variabili_sessione.php");
    include_once('../lib/navbar.php');
        messaggi_errore();
?>
    <div>
        <?php  print("<h1>Benvenuto ".  $_SESSION['nome']." ". $_SESSION['cognome'] ."</h1>");?>
    </div>
    <div>
        esami programmati dal docente<br>
        <div class= "table-container">
        <table class="table">
            <thead>
                <tr>
                    <th> Insegnamento </th>
                    <th> Data </th>
                    <th> Opzioni </th>
                </tr>
            </thead>
            <?php // generazione di tutte le righe della tabella
            $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
                $sql = "SELECT insegnamento.nome as insegnamento_n, data, esami.id as esami_id
                        FROM esami 
                        JOIN insegnamento ON insegnamento.id = esami.insegnamento
                        WHERE docente = $1";
                $prepare = pg_prepare($conn, "esami_in_programma", $sql);
                if ($prepare) {
                    $esami_in_prog = pg_execute($conn, "esami_in_programma", array($_SESSION['id']));
                    while ($row = pg_fetch_assoc($esami_in_prog)) {
                        print('
                            <tr>
                                <td>' . $row['insegnamento_n'] . '</td>
                                <td>' . $row['data'] . '</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Azioni">
                                        <button type="button" onclick="redirectToUpdate(\'cancella\', ' . $row['esami_id'] . ')" class="btn btn-outline-primary">Cancella</button>
                                        <button type="button" onclick="redirectToUpdate(\'modifica\', ' . $row['esami_id'] . ')" class="btn btn-outline-primary">Modifica</button>
                                    </div>
                                </td>
                            </tr>
                            <script>
                                function redirectToUpdate(operazione, esame) {
                                    if (operazione === \'cancella\') {
                                        var url = "cancella_esame.php?esame=" + encodeURIComponent(esame);
                                        window.location.href = url;
                                    } else if (operazione === \'modifica\') {
                                        var url = "update_esame.php?esame=" + encodeURIComponent(esame);
                                        window.location.href = url;
                                    }
                                }
                            </script>');
                    }
                }else{
                    print("preparazione della query fallita");
                }
            ?>
        </table>

    </div>
    </form>

    <div style="padding:2%;text-align: center;"> 
        <hr> 
        <h3>programma un nuovo esame:</h3>
    </div>

    </div>
        <?php include_once('../lib/form_inserisci_esame.php');?>
    </div>

    <div style ="padding : 1%"><hr></div><!------------------------------------------------------------------->

    <div style="display: flex; justify-content: center; height: 10vh; border:2px solid blue;">
        <div style="display: grid; gap: 10px; justify-items: center; text-align: center;">
            <h3>inserzione esiti</h3>
            <form action="registra_voti.php" method="POST">
                <div style="padding:2%">
                    <label for="m_studente">Matricola studente:</label>
                    <input type="text" name="m_studente" id="m_studente">
                </div>

                <div style="padding:2%">
                    <label for="esame">Esame:</label>
                    <select class="form-select" name="esame" id="esame" aria-label="Default select example">
            
                    <?php 
                        if($esami_in_prog){
                            print("<div>insegnamenti trovatioo</div>". pg_num_rows($esami_in_prog));
                            while($row = pg_fetch_assoc($esami_in_prog)){
                                echo("<option value='" . $row['insegnamento_n'] . "'>" . $row['insegnamento_n'] . "</option>");
                            }
                        }else{
                        print("<div>insegnamenti non trovati</div>");
                        }
                    ?>
                </div>

                <div style="padding:2%">
                    <label for="esito">Esito:</label>
                    <input type="number" name="esito" id="esito" min="0" max="30">
                </div>
                
                <div style="padding:2%">
                    <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
                </div>
            </form>
        </div>
    </div>

    <?php //form per il cambio password 
        include_once('../lib/cambio_password.php');
    ?>

</body> 
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</html>