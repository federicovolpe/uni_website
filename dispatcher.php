<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $email = $_POST['email'];
  $password = $_POST['password'];
  $tipologia = $_POST['tipologia'];
  $additionalParam = $_POST['additionalParam'];

  // Process the form data and redirect to the appropriate page
  if ($tipologia === 'studente') {$redirectUrl = 'studente.php?email=' . urlencode($email) . '&password=' . urlencode($password);
    header('Location: ' . $redirectUrl);
    exit;
  } elseif ($tipologia === 'docente') {$redirectUrl = 'docente.php?email=' . urlencode($email) . '&password=' . urlencode($password);
    header('Location: ' . $redirectUrl);
    exit;
  } elseif ($tipologia === 'segreteria') {$redirectUrl = 'segreteria.php?email=' . urlencode($email) . '&password=' . urlencode($password);
    header('Location: ' . $redirectUrl);
    exit;
  }
}
?>
