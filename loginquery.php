<?php
print("ciao sto facendo la tua query</br>");

// Recupero dei dati dal modulo di accesso
if(isset($_GET['email']) && isset($_GET['password'])){
    $email = $_GET['email'];
    $typee = gettype($email);
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
        $query = "
            SELECT 1
            FROM studente
            WHERE email = $1 AND passwrd = $2 ;";
        $prepara = pg_prepare($conn, "query_di_verifica", $query);
        $result = pg_execute($conn, "query_di_verifica", array($email, $password));

        // Elaborazione dei risultati
        while ($row = pg_fetch_assoc($result)) {
            echo "Nome " . $row['nome'] . ", Cognome: " . $row['cognome'] . ", email: " . $row['email'] . "<br>";
        }

        // Controllo se le credenziali sono valide
        if (pg_num_rows($result) >= 1) {
            // Accesso valido, reindirizzamento alla pagina successiva
            header("Location: pagina_studente.php");
        } else {
            // Accesso non valido, reindirizzamento a pagina di errore
            $url_errore ="login.html?error=" . urlencode(1);
            header("Location: " . $url_errore);
        }
        // Chiusura della connessione al database
        pg_close($conn);
    }else{  
        print("connessione fallita<br>");
        print("ti riporto al sito precedente<br>ciaociao");
        $url_errore ="login.html?error=" . urlencode(404);
        header("Location: ". $url_errore);
        exit;
    }
}
?>

