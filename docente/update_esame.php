<?php
//obiettivo: modificare la data dell'esame
    $esame_id = $_GET['esame'];
    $newDate = $_POST['new_date'];
    print("esame : ". $esame_id."</br>");
$nuova_data_formattata = date_format(date_create($newDate), 'dmy');
    print("data : ". $formattedDate."</br>");
    
    $db = pg_connect("dbname=unimio host=localhost port=5432");
        $sql = "UPDATE esami SET data = $1 WHERE id = $2";
        $preparazione = pg_prepare($db , "update", $sql);

        if($preparazione){
            pg_execute($db, "update", array($nuova_data_formattata,$esame_id));
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
    <h1>Update Esame</h1>
        <form method="POST" action="">
            <label for="new_date">New Date:</label>
            <input type="date" id="new_date" name="new_date" required>
            <input type="submit" value="Update">
        </form>
    </body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>