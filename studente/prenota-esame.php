<?PHP 
    //fetch degli esami disponibili per lo studente corrente
    session_start();
    $matricola = $_SESSION['matricola'];
    if(!empty($matricola)){
        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($db){
            //query
            $tutti_gli_esami = "SELECT * FROM esami";
            $esami_prenotati = "SELECT * FROM esami_prenotati WHERE matricola = $1";
            // devo crearmi una colonna per segnarmi quale esame è prenotato e quale no cosi
            // ho tutto in una tabella dalla quale posso popolare la tabella della pagina
        }else{
            print("connessione al database fallita");
        }
        
    }else{
        print("matricola non pervenuta");
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
        <?php  print("<h2>esami a cui ti puoi prenotare</h2>");?>
    <div>
        <div class= "table-container">
        <table class="table-striped">
        <caption><?php print("corso: ". $corso_frequentato . " utente: " . $nome . " " . $cognome) ?></caption>
            <thead>
                <tr>
                    <th> Materia </th>
                    
                    <th> Data </th>
                    <th> <form action=""><input type="submit"> </th>
                </tr>
            </thead>
            <tr>
                <td> %materia1 </td>
                <td> %voto </td>
                <td> %data </td>
            </tr>
            <tr>
                <td> %materia2 </td>
                <td> %voto </td>
                <td> %data </td>
            </tr>
            <tr>
                <td> %materia3 </td>
                <td> %voto </td>
                <td> %data </td>
            </tr>
        </table>
    </div>
        
    <div>
        <h2>vuoi iscriverti ad un esame? </h2>
        <a href="iscrizione esame"> iscriviti ad un esame! </a>
    </div>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>