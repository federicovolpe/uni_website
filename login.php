<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css">
        <style>
            body{
                background-color: black;
            }
        </style>
</head>

<body>
<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="immagini/logo_unimi.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
      Università degli Studi di Milano
    </a>
  </div>
</nav>
    <div class= "login-form">
        <div style="display: flex;
            flex-direction: column;
            align-items: center;">
            <h1 class="text-center" style="color: blue">Benvenuto nella pagina di login</h1>

            <div class="login-form">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="matricola">Matricola</span>
                    <input type="text" class="form-control" placeholder="Inserisci qui la tua matricola" aria-label="Username" aria-describedby="basic-addon1">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="codice_accesso">Password</span>
                    <input type="password" class="form-control" id="password" placeholder="Inserisci qui la tua password" aria-label="Password" aria-describedby="basic-addon1">
                    <div class = "estensioni">
                        <input type="checkbox" id="show-password">Mostra<label for="show-password"></label>
                        
                        <script>
                        var passwordInput = document.getElementById("password");
                        var showPasswordCheckbox = document.getElementById("show-password");

                        // listener per l'evento di cambio stato del "mostra password"
                        showPasswordCheckbox.addEventListener("change", function() {
                            if (showPasswordCheckbox.checked) {
                                // Se il checkbox è selezionato, mostra la password
                                passwordInput.type = "text";
                            } else {
                                // Altrimenti nascondi
                                passwordInput.type = "password";
                            }
                        });
                        </script>
                    </div>
                    
                </div><select class="form-select" aria-label="Default select example">
                        <option selected>seleziona tipo di utente</option>
                        <option value="studente">Studente</option>
                        <option value="docente">Docente</option>
                        <option value="segreteria">Segreteria</option>
                    </select>


                <row>
                    <!--sezione accedi ricorda-->
                    <input type="checkbox" id="Ricordami">
                    <label for="Ricordami">Ricordami</label>
                    <form action="login.php">
                        <input type="submit" id="accedi" label="Accedi" style="float: right" value="Accedi">
                    </form>
                </row>
            </div>
        </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>