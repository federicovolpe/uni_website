<!--  pagina del dispatcher con il compito di verificare le credenziali e redirezionare alla pagina d'utente corretta -->

<?php
    include('../lib/functions.php');
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      //recupero delle informazioni necessarie per il dispatching
      $email = $_POST['email'];
      $password = $_POST['password'];

      // nel caso si tratti di uno studente
      if (substr($email, -17) === 'studenti.unimi.it') {
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['email'] = $_POST['email'];

        //recupero le informazioni dello studente e vai alla pagina
        verifica_recupera_info(); 

        if($_POST['approved'] == 0){ 
          $redirectUrl = 'studente/studente.php';
          header('Location: ' . $redirectUrl);
          exit;
        }

      } elseif (substr($email, -16) === 'docenti.unimi.it') {
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['email'] = $_POST['email'];

        //recupero le informazioni del docente e vai alla pagina
        verifica_recupera_info();

        if($_POST['approved'] == 0){ 
          $redirectUrl = 'docente/docente.php';
          header('Location: ' . $redirectUrl);
          exit;
        }

      } elseif (substr($email, -19) === 'segreteria.unimi.it') {
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['email'] = $_POST['email'];

        //recupero le informazioni della segreteria e vai alla pagina
        verifica_recupera_info();

        if($_POST['approved'] == 0){ 
          $redirectUrl = 'segreteria/segreteria.php';
          header('Location: ' . $redirectUrl);
          exit;
        }
      }
    }
?>