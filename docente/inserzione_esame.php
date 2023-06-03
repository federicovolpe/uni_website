<?php
//obiettivo: modificare la data dell'esame
session_start();
                                    $id_docente = $_SESSION['id'];
    $insegnamento = $_POST['insegnamento'];
    $date = $_POST['date'];
    print("esame : ". $esame_id."</br>");
$nuova_data_formattata = date_format(date_create($date), 'dmy');
    
    $db = pg_connect("dbname=unimio host=localhost port=5432");
        $sql = "INSERT INTO esami (insegnamento, docente, data) VALUES ($1, $2, $3)";
        $preparazione = pg_prepare($db , "update", $sql);

        if($preparazione){
            pg_execute($db, "update", array($insegnamento, $id_docente, $nuova_data_formattata));
            print("update dell'esame");

            $url_errore ="update_esame.php?approved=" . urlencode(0) ;
            //header("Location: " . $url_errore);
            //exit;
        }else{
            print("qualcosa è andato storto nella preparazione della query");
            $url_errore ="update_esame.php?approved=" . urlencode(1) . "&msg="  . urlencode("la modifica dell'esame non è andata a buon fine");
            header("Location: " . $url_errore);
            exit;
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../immagini/logo_unimi.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> Università degli Studi di Milano
            </a>
        </div>
    </nav>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const approved = urlParams.get('approved');
        const msg = urlParams.get('msg');
        if (approved === '0') {
            var successMessage = document.createElement('div');
            successMessage.className = 'p-3 mb-2 bg-success text-white';
            successMessage.textContent = 'operazione approvata dal database';
            document.body.appendChild(successMessage);
        }
        if (approved === '1') {
            var successMessage = document.createElement('div');
            successMessage.className = 'p-3 mb-2 bg-danger text-white';
            successMessage.textContent = msg;
            document.body.appendChild(successMessage);
        }
    </script>

    <div style="text-align: center;"><h3>inserisci o modifica un corso</h3></div>
    
        <form class="form-segreteria" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <select class="form-select" name="insegnamento" id="insegnamento" aria-label="Default select example">
                                <!--opzioni fra gli insegnamenti del professore specificato-->
                                <?php
                                    print("dalla sessione ripesco l'id_docente: ". $id_docente);
                                    $db = pg_connect("dbname=unimio port=5432 host=localhost");
                                    if($db){
                                        $sql = "SELECT I.nome AS n_insegnamento, R.docente AS docente
                                                FROM insegnamento AS I
                                                JOIN responsabile_insegnamento AS R ON R.insegnamento = I.id
                                                WHERE R.docente = $1";
                                        $prepare = pg_prepare($db, "insegnamenti_docente", $sql);
                                        if($prepare){
                                            $result = pg_execute($db, "insegnamenti_docente", array($id_docente));
                                            if($result){
                                                while($row = pg_fetch_assoc($result)){
                                                    echo('<option value="'. $row['n_insegnamento'] .'">'. $row['n_insegnamento']. '</option>');
                                                }
                                            }else{
                                                print("l'esecuzione della query non è andata a buon fine");
                                            }
                                        }else{
                                            print("la preparazione della query non è riuscita");
                                        }
                                    }else{
                                        print("la connessione al db non è riuscita");
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

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>