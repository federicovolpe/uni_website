<!--   homepage dell'utente docente   -->

<?php
    //include delle funzioni
    include("../lib/functions.php");
    session_start();
 
    //se si è tentato di eseguire un cambio password
    if(isset($_GET['change_password'])){
        include_once('../lib/change_password.php');
    }

    //se il professore ha tentato di inserire un esame allora richiamo lo script
    if (isset($_GET['inserisci_esame'])) {
        //codice sql per inserire un esame
        include_once('funzioni_docente/inserzione_esame.php');
    }

    //se il professore ha tentato l'inserimento di un esito allora richiamo lo script
    if (isset($_GET['inserisci_esiti'])) {
        //codice sql per inserire un esito
        include_once('funzioni_docente/sql_inserzione_esiti.php');
    }

    //se il professore ha tentato la cancellazione di un esame allora richiamo lo script 
    if (isset($_GET['cancella_esame'])) {
        //codice sql per cancellare un esame
        include_once('cancella_esame.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once("../lib/head.php"); ?>

<body style="background-color: white">

    <?php include_once('../lib/navbar.php'); ?>


    <!--                                       tabella degli esami programmati                                 -->
    <div style="margin-top: 3%;margin: 2%;">
        <h3>esami programmati dal docente</h3>
        <div class="table-container">
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

                // query per recuperare le informazioni degli esami che il professore ha programmato (nome insegnamento, data)
                $sql = "SELECT insegnamento.nome as insegnamento_n, data, esami.id as esami_id
                        FROM esami 
                        JOIN insegnamento ON insegnamento.id = esami.insegnamento
                        WHERE docente = $1";

                $prepare = pg_prepare($conn, "esami_in_programma", $sql);
                if ($prepare) {
                    $esami_in_prog = pg_execute($conn, "esami_in_programma", array($_SESSION['id']));
                    while ($row = pg_fetch_assoc($esami_in_prog)) {

                        //print delle opzioni di modifica e cancellazione dell'esame
                        print('<tr>
                                <td>' . $row['insegnamento_n'] . '</td>
                                <td>' . $row['data'] . '</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <form action="' . $_SERVER['PHP_SELF'] . '?cancella_esame=' . $row['esami_id'] . '" method="POST">
                                            <button type="submit" class="btn btn-danger">Cancella Esame</button>
                                        </form>
                                        <form action="funzioni_docente/update_esame.php?esame=' . $row['esami_id'] . '" method="POST">
                                            <button type="submit" class="btn btn-primary">Modifica</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        ');
                    }
                } else {
                    print("preparazione della query fallita");
                }
                ?>
            </table>
        </div>
    </div>

    <div style="padding:2%;">
            <hr></hr>
    </div>

   <!----------------------------     inserzione di un nuovo esame     --------------------------------------->
    <?php include_once('funzioni_docente/form_inserisci_esame.php'); ?>

    <div style="padding : 1%">
        <hr></hr>
    </div>
    
    <!----------------------------     inserzione degli esisti per gli studenti      --------------------------------------->
    <div>
        <?php include_once('funzioni_docente/form_inserzione_esiti.php'); ?>
    </div>

   <!----------------------------     form per il cambio password       --------------------------------------->
    <div>
        <?php include_once('../lib/cambio_password.php') ?>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>