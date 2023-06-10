<?php
    //include delle funzioni
    include("../lib/functions.php");
    session_start();
    //se il professore ha tentato di inserire un esame
    if(isset($_GET['inserisci_esame'])){
        //codice sql per inserire un esame
        print('inserimento esame');
        include_once('funzioni_docente/inserzione_esame.php');
    }
    if(isset($_GET['inserisci_esiti'])){
        //codice sql per inserire un esito
        include_once('funzioni_docente/sql_inserzione_esiti.php');
    }
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
        messaggi_errore_post2();
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
                    <th> Dataa </th>
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
        <?php include_once('funzioni_docente/form_inserisci_esame.php');?>
    </div>

    <div style ="padding : 1%"><hr></div><!------------------------------------------------------------------->

    <?php print'ciao'; include_once('funzioni_docente/form_inserzione_esiti.php');?>

    <?php //form per il cambio password 
        include_once('../lib/cambio_password.php');
    ?>

</body> 
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</html>