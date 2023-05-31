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
    $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
    if($conn){
        $query = "SELECT 1
                FROM studente
                WHERE email = $1 AND passwrd = $2 ;";
        $prepara = pg_prepare($conn, "query_di_verifica", $query);
        $esito_verifica = pg_execute($conn, "query_di_verifica", array($email, $password));

        if(pg_num_rows($esito_verifica) >= 1){
            $query2 = " SELECT *
                FROM studente
                WHERE email = $1 AND passwrd = $2 ;";
            $prepara = pg_prepare($conn, "fetch_info", $query2);
            $result = pg_execute($conn, "fetch_info", array($email, $password));
            //print("ho trovato qualcosa, righe : ". pg_num_rows($result) . "</br>");
            if($result){ 
                
                //se la query riesce a raccogliere dei dati allora li memorizzo
                $row = pg_fetch_assoc($result);
                $matricola = $row['matricola'];
                //print("matricola: $matricola</br>");
                $nome = $row['nome'];
                //print("nome: $nome</br>");
                $cognome = $row['cognome']; 
                //print("cognome: $cognome</br>");
                $corso_frequentato = $row['corso_frequentato'];
            }
        } else {
            // Accesso non valido, reindirizzamento a pagina di errore
            print('credenziali non trovate');
            $url_errore ="login.html?error=" . urlencode(1);
            //header("Location: " . $url_errore);
            //exit;
        }
        // Chiusura della connessione al database
        pg_close($conn);
    }else{  
        print("connessione fallita<br>");
        print("ti riporto al sito precedente<br>");
        $url_errore ="login.html?error=" . urlencode(404);
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
        </div>
    </nav>
        <?php  print("<h1>Benvenuto $nome $cognome</h1>");?>
    <div>
        questa è la homepage della segreteria<br>
        definizione di uno sudente/docente<br>
    modifica di uno studente/docente<br>
    rimozione di uno studente/docente<br>
    definizione di un corso di laurea triennale/magistrale<br>

    
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>