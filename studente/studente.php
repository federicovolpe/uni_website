<?php
// Recupero dei dati dal modulo di accesso
    session_start();
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
if(!empty($email) && !empty($password)){
    /*print("tentato l'accesso con le credenziali: <br>");
    print("email: $email -</br>");
    print("password: $password -</br>");*/
}
    //Connessione al database
    $db = pg_connect("host = localhost port = 5432 dbname = unimio");
    if($db){
        $query = "SELECT 1
                FROM studente
                WHERE email = $1 AND passwrd = $2 ;";
        $prepara = pg_prepare($db, "query_di_verifica", $query);
        $esito_verifica = pg_execute($db, "query_di_verifica", array($email, $password));

        if(pg_num_rows($esito_verifica) >= 1){
            $query2 = " SELECT *
                FROM studente
                WHERE email = $1 AND passwrd = $2 ;";
            $prepara = pg_prepare($db, "fetch_info", $query2);
            $result = pg_execute($db, "fetch_info", array($email, $password));

            if($result){ 
                //se la query riesce a raccogliere dei dati allora li memorizzo
                $row = pg_fetch_assoc($result);
                $matricola = $row['matricola'];
                print("matricola: ".$matricola."</br>");
                $nome = $row['nome'];
                $cognome = $row['cognome']; 
                $corso_frequentato = $row['corso_frequentato'];

                session_start();
                $_SESSION['matricola'] = $matricola;
                /*print("matricola: $matricola</br>");
                print("nome: $nome</br>");
                print("cognome: $cognome</br>");*/
            }
        } else {
            // Accesso non valido, reindirizzamento a pagina di errore
            print('credenziali non trovate');
            $url_errore ="../login.html?error=" . urlencode(1);
            header("Location: " . $url_errore);
            exit;
        }
        // Chiusura della connessione al database
        pg_close($db);
    }else{  
        print("connessione fallita<br>");
        print("ti riporto al sito precedente<br>");
        $url_errore ="../login.html?error=" . urlencode(404);
        header("Location: ". $url_errore);
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
            <button type="button" onclick="window.location.href='../login.html'" class="btn btn-outline-primary">logout</button>

        </div>
    </nav>
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
                    $db = pg_connect("host = localhost port = 5432 dbname = unimio");
                    if($db){
                        $sql = "SELECT ES.esame_id, ES.nome, ES.data
                                FROM studente AS S
                                JOIN
                                    (SELECT I.corso AS corso, E.id AS esame_id, I.nome, E.data
                                    FROM esami AS E
                                    INNER JOIN insegnamento AS I ON I.id = E.insegnamento) AS ES ON ES.corso = S.corso_frequentato
                                WHERE S.matricola = $1";
                        $preparato = pg_prepare($db, "esami_iscrivibili", $sql);

                        if($preparato){
                            $result = pg_execute($db, "esami_iscrivibili", array($matricola));
                            if($result){
                                while($row = pg_fetch_assoc($result)){
                                    echo("<tr>
                                            <td> ". $row['nome']. "</th>
                                            <td> voto </th>
                                            <th> ". $row['data']. "</th>
                                            <th> form iscrizione </th>
                                          </tr>");
                                }
                            }else{
                                print("l'esecuzione della query non è andata a buon fine</br>");
                            }
                        }else{
                            print("la preparazione della query non è andata a buon fine</br>");
                        }
                    } else{
                        print("connessione con il database fallita");
                    }
                ?>
        </table>
    </div>
        

    <form action="../change_password.php" style="padding: 5%; justify-content: center; align-items: center; display: flex;" method="POST">
        <h4>vuoi cambiare password? </br></h4>
        <label for="password">password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" style="padding:2%;" class="btn btn-primary">Cambia</button>
    </form>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>

