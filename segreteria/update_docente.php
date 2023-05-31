<?php
if (isset($_POST))  {
    // Retrieve the name and surname from the form submission
    $id = $_POST['id'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];

    // Perform the database insertion
    $db = pg_connect("host=localhost port=5432 dbname=unimio");

    if ($db) {
        $sql = "INSERT INTO docente (name, surname) VALUES ($1, $2, $3, $4, $5)";
        $result = pg_prepare($db, "insert_docente", $sql);

        if ($result) { //se la preparazione della query va a buon fine allora la eseguo
            $inserito = pg_execute($db, "insert_docente", array($id, $nome, $cognome, $email, $password));

            if ($inserito) {
                echo "il docente è stato inserito";
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
