<?php
    session_start();
    include("lib/functions.php");
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Retrieve form data
      $email = $_POST['email'];
      $password = $_POST['password'];
      
      //salvo la tipologia dell'utente perchè serve nella query del cambio password
      
      

      // Process the form data and redirect to the appropriate page
      if (substr($email, -17) === 'studenti.unimi.it') {
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['email'] = $_POST['email'];

        //recupero le informazioni dello studente e vai alla pagina
        verifica_recupera_info();
        print("dati recuperati con successo");
        $redirectUrl = 'studente/studente.php';
        header('Location: ' . $redirectUrl);
        exit;

      } elseif (substr($email, -16) === 'docenti.unimi.it') {
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['email'] = $_POST['email'];

        //recupero le informazioni del docente e vai alla pagina
        verifica_recupera_info();
        print("dati recuperati con successo");
        $redirectUrl = 'docente/docente.php';
        header('Location: ' . $redirectUrl);
        exit;
      } elseif (substr($email, -19) === 'segreteria.unimi.it') {
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['email'] = $_POST['email'];

        //recupero le informazioni della segreteria e vai alla pagina
        verifica_recupera_info();
        print("dati recuperati con successo");
        $redirectUrl = 'segreteria/segreteria.php';
        header('Location: ' . $redirectUrl);
        exit;
      }
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <!-- semplice testo per far notare che il login è fermo alla pagina del dispatcher -->
  <h1>sei finito nella pagina del dispatchero</h1>

  <?php
    include_once("lib/variabili_sessione.php");
  ?>
</body>
</html>