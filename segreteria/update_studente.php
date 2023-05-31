<?php
if (isset($_POST))  {
    // Retrieve the name and surname from the form submission
    $matricola = $_POST['matricola'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $corso_frequentato = $_POST['corso_frequentato'];

    // Perform the database insertion
    $db = pg_connect("host=localhost port=5432 dbname=unimio");

    if ($db) {
        $sql = "INSERT INTO studente (matricola, nome, cognome, email, passwrd, corso_frequentato) VALUES ($1, $2, $3, $4, $5, $6)";
        $result = pg_prepare($db, "insert_docente", $sql);

        if ($result) { //se la preparazione della query va a buon fine allora la eseguo
            $inserito = pg_execute($db, "insert_docente", array($matricola, $nome, $cognome, $email, $password, $corso_frequentato));

            if ($inserito) {
                echo "lo studente è stato inserito";
                $url_errore ="segreteria.php?approved=" . urlencode(1);
                header("Location: " . $url_errore);
                exit;
            } else {
                echo "il docente non è stato inserito";
            }
        } else {
            echo "Failed to prepare the insertion query.";
        }

        pg_close($db);
    } else {
        echo "connessione al database fallita";
    }
}
?>
