<?php
    include('../lib/functions.php');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Retrieve form data
      $email = $_POST['email'];
      $password = $_POST['password'];
      print('email = '.$email.'<br>');
      print('password = '.$password.'<br>');

      // Process the form data and redirect to the appropriate page
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