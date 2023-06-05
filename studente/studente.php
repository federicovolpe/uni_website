<?php
    //include delle funzioni
    include("../lib/functions.php");
    verifica_recupera_info();
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
        <?php
            //include del file navbar.php
            include("../lib/navbar.php");
        ?>
        <?php messaggi_errore()?>
        <?php  print("<h1>Benvenuto $nome $cognome</h1>");?>
    <div>
        questa è la homepage dello studente<br>
        ecco i tuoi voti :

        <!-- inizio tabella -->
    <div>corso frequentato: <?php print($corso_frequentato) ?></div>
        <div class= "table-container">
        <table class="table-striped">
        
            <thead>
                <tr>
                    <th> Materia </th>
                    <th> Voto </th>
                    <th> Data </th>
                    <th> Iscrizione</th>
                </tr>
            </thead>
                <?php
                    display_esami_tablella($matricola);
                ?>
        </table>
    </div>
        

    <form action="../change_password.php" style="padding: 5%; justify-content: center; align-items: center; display: flex;" method="POST">
        <h4>vuoi cambiare password? </br></h4>
        <label for="password">password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" style="padding:2%;" class="btn btn-primary">Cambia</button>
    </form>
    <?php script_boostrap()?>
</body>
</html>

