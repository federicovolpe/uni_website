<?php
print("ciao sto facendo la tua query");

// Recupero dei dati dal modulo di accesso
$username = $_GET['username'];
$password = $_GET['password'];
print("eseguito l'accesso con le credenziali: <br>");
print("nome: $username -</br>");
print("password: $password -");
/* Connessione al database
$conn = pg_connect("host = localhost dbname = studentidb user=nomeutente password = password");

// Esecuzione della query per verificare le credenziali
$query = "SELECT * FROM tabella_utenti WHERE username = '$username' AND password = '$password'";
$result = pg_query($conn, $query);

// Controllo se le credenziali sono valide
if (pg_num_rows($result) > 0) {
  // Accesso valido, reindirizzamento alla pagina successiva
  header("Location: pagina_successiva.html");
} else {
  // Accesso non valido, reindirizzamento a pagina di errore
  header("Location: pagina_errore.html");
}

// Chiusura della connessione al database
pg_close($conn);*/
?>