<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $email = $_POST['email'];
  $password = $_POST['password'];
  $tipologia = $_POST['tipologia'];
  $additionalParam = $_POST['additionalParam'];

  // Process the form data and redirect to the appropriate page
  if ($tipologia === 'studente') {
    session_start();
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['email'] = $_POST['email'];
    $redirectUrl = 'studente/studente.php';
    header('Location: ' . $redirectUrl);
    exit;
  } elseif ($tipologia === 'docente') {
    session_start();
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['email'] = $_POST['email'];
    $redirectUrl = 'docente/docente.php';
    header('Location: ' . $redirectUrl);
    exit;
  } elseif ($tipologia === 'segreteria') {
    session_start();
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['email'] = $_POST['email'];
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
  <h1>sei finito nella pagina del dispatcher</h1>
</body>
</html>