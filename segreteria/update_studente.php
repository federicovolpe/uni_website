<?php
if (isset($_POST))  {
    // Retrieve the name and surname from the form submission
    $matricola = $_POST['matricola'];
    if (isset($_POST['email'])){
        $email = $_POST['email'];
    }
    if (isset($_POST['password'])){
        $password = $_POST['password'];
    }
    if (isset($_POST['nome'])){
        $nome = $_POST['nome'];
    }
    if (isset($_POST['cognome'])){
        $cognome = $_POST['cognome'];
    }
    if (isset($_POST['corso_frequentato'])){
        $corso_frequentato = $_POST['corso_frequentato'];
    }
    $operazione = $_POST['operazione'];

    // connessione al database
    $db = pg_connect("host=localhost port=5432 dbname=unimio");

    if ($db) {
        switch($operazione){
            case 'aggiungi':
                $sql = "INSERT INTO studente (matricola, nome, cognome, email, passwrd, corso_frequentato) VALUES ($1, $2, $3, $4, $5, $6)";
                $result = pg_prepare($db, "op_studente", $sql);
                
                if ($result) { //se la preparazione della query va a buon fine allora la eseguo
                    $inserito = pg_execute($db, "op_studente", array($matricola, $nome, $cognome, $email, $password, $corso_frequentato));

                    if ($inserito) {
                        echo "lo studente è stato inserito";
                        $url_successo ="segreteria.php?approved=" . urlencode(0);
                        header("Location: " . $url_successo);
                        exit;
                    } else {
                        echo "lo studente non è stato inserito";
                        $url_errore ="segreteria.php?approved=" . urlencode(1);
                        header("Location: " . $url_errore);
                        exit;
                    }
                } else {
                    echo "qualcosa è andato storto nella preparazione della query.";
                }

            case 'modifica':
                    $sql = "UPDATE studente SET $1 = $2 WHERE matricola = $3";



            case 'cancella':
                    $sql = "DELETE FROM studente WHERE matricola = $1";
                    $result = pg_prepare($db, "op_studente", $sql);
                
                if ($result) { //se la preparazione della query va a buon fine allora la eseguo
                    $cancellato = pg_execute($db, "op_studente", array($matricola));

                    if ($cancellato) {
                        echo "lo studente è stato cancellato";
                        $url_successo ="segreteria.php?approved=" . urlencode(0);
                        header("Location: " . $url_successo);
                        exit;
                    } else {
                        echo "lo studente non è stato cancellato";
                        $url_errore ="segreteria.php?approved=" . urlencode(1);
                        header("Location: " . $url_errore);
                        exit;
                    }
                } else {
                    echo "qualcosa è andato storto nella preparazione della query.";
                }
            default:
                echo("qualcosa è andato storto nella selezione dell'operazione");
                break;
        }

        pg_close($db);
    } else {
        echo "connessione al database fallita";
    }
    echo("il $_POST non è settato!");
}
?>
