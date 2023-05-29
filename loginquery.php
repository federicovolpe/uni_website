<?php
print("ciao sto facendo la tua query</br>");

// Recupero dei dati dal modulo di accesso
if(isset($_GET['email']) && isset($_GET['password'])){
    $email = $_GET['email'];
    $password = $_GET['password'];
    $tipologia = $_GET['tipologia'];
    print("tentato l'accesso con le credenziali: <br>");
    print("email: $email $typee -</br>");
    print("password: $password -</br>");
    print("tipologia: $tipologia -");

    //Connessione al database
    $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
    if($conn){
        // Esecuzione della query per verificare le credenziali
        switch($tipologia){
            case 'studente':
                $query = "SELECT 1
                    FROM studente
                    WHERE email = $1 AND passwrd = $2 ;";
                $prepara = pg_prepare($conn, "query_di_verifica", $query);
                $result = pg_execute($conn, "query_di_verifica", array($email, $password));
            case 'docente':
                $query = "SELECT 1
                    FROM docente
                    WHERE email = $1 AND passwrd = $2 ;";
                $prepara = pg_prepare($conn, "query_di_verifica", $query);
                $result = pg_execute($conn, "query_di_verifica", array($email, $password));
            case 'segreteria':
                $query = "SELECT 1
                    FROM segreteria
                    WHERE email = $1 AND passwrd = $2 ;";
                $prepara = pg_prepare($conn, "query_di_verifica", $query);
                $result = pg_execute($conn, "query_di_verifica", array($email, $password));
            default:
                break;
        }
        
        if (pg_num_rows($result) >= 1) {
            // Accesso valido, reindirizzamento alla pagina successiva
            $url_accesso = $tipologia .".php";
            header("Location: ". $url_accesso);
        } else {
            // Accesso non valido, reindirizzamento a pagina di errore
            $url_errore ="login.html?error=" . urlencode(1);
            header("Location: " . $url_errore);
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
}
?>

