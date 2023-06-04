<?php
// Recupero dei dati dal modulo di accesso
session_start();
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if (!empty($email) && !empty($password)) {

    /*print("tentato l'accesso con le credenziali: <br>");
    print("email: $email -</br>");
    print("password: $password -</br>");*/
}
$conn = pg_connect("host = localhost port = 5432 dbname = unimio");
if ($conn) {
    $query = "SELECT 1
                FROM segreteria
                WHERE email = $1 AND passwrd = $2 ;";
    $prepara = pg_prepare($conn, "query_di_verifica", $query);
    $esito_verifica = pg_execute($conn, "query_di_verifica", array($email, $password));

    if (pg_num_rows($esito_verifica) >= 1) {
        $query2 = " SELECT *
                FROM segreteria
                WHERE email = $1 AND passwrd = $2 ;";
        $prepara = pg_prepare($conn, "fetch_info", $query2);
        $result = pg_execute($conn, "fetch_info", array($email, $password));

        if ($result) {
            //se la query riesce a raccogliere dei dati allora li memorizzo
            $row = pg_fetch_assoc($result);
            $nome = $row['nome'];
            $cognome = $row['cognome'];

                //salvo nella sessione l'id per la query del cambio password
                session_start();
                $_SESSION['id'] = $row['id'];

        }
    } else {
        // Accesso non valido, reindirizzamento a pagina di errore
        print('credenziali non trovate');
        $url_errore = "../login.html?error=" . urlencode(1);
        header("Location: " . $url_errore);
        exit;
    }
    // Chiusura della connessione al database
    pg_close($conn);
} else {
    print("connessione fallita<br>");
    print("ti riporto al sito precedente<br>");
    $url_errore = "../login.html?error=" . urlencode(404);
    header("Location: " . $url_errore);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../stylesheet.css">

</head>

<body>
            <script>
                const urlParams = new URLSearchParams(window.location.search);
                const approved = urlParams.get('approved');
                const msg = urlParams.get('msg');
                if (approved === '0') {
                    var successMessage = document.createElement('div');
                    successMessage.className = 'p-3 mb-2 bg-success text-white';
                    successMessage.textContent = 'operazione approvata dal database';
                    document.body.appendChild(successMessage);
                }
                if (approved === '1') {
                    var successMessage = document.createElement('div');
                    successMessage.className = 'p-3 mb-2 bg-danger text-white';
                    successMessage.textContent = msg;
                    document.body.appendChild(successMessage);
                }
            </script>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../immagini/logo_unimi.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> Università degli Studi di Milano
            </a>
        </div>
    </nav>
    <div style="text-align:center;"><?php print("<h1>Benvenuto $nome $cognome</h1></br>
            <h3> questa è la homepage della segreteria</h3>"); ?></div>
    <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">


        <div class="row">
            <div class="col-sm-6">
                <h3>modifica o aggiungi un docente</h3>
                <form class="form-segreteria" action="update_docente.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                <div class="input-group mb-3">
                                        <span class = "input-group-text">Id:</span>
                                        <input type="text" class="form-control" name="id" id="id" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Email:</span>
                                        <input type="text" class="form-control" name="email" id="email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Nome:</span>
                                        <input type="text" class="form-control" name="nome" id="nome">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Cognome:</span>
                                        <input type="text" class="form-control" name="cognome" id="cognome">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                <div class="input-group mb-3">
                                        <span class = "input-group-text">Password:</span>
                                        <input type="text" class="form-control" name="password" id="password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                            <option value="aggiungi">Aggiungi</option>
                            <option value="modifica">Modifica</option>
                            <option value="cancella">Cancella</option>
                        </select>
                    </div>

                    <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
                </form>
            </div>

            <div class="col-sm-6">
                <h3>Modifica o aggiungi uno studente</h3>
                <form class="form-segreteria" action="update_studente.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                <div class="input-group mb-3">
                                        <span class = "input-group-text">Matricola:</span>
                                        <input type="text" class="form-control" name="matricola" id="matricola" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Email:</span>
                                        <input type="text" class="form-control" name="email" id="email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Nome:</span>
                                        <input type="text" class="form-control" name="nome" id="nome">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Cognome:</span>
                                        <input type="text" class="form-control" name="cognome" id="cognome">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                <div class="input-group mb-3">
                                        <span class = "input-group-text">Password:</span>
                                        <input type="text" class="form-control" name="password" id="password">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Corso:</span>
                                        <input type="text" class="form-control" name="corso_frequentato" id="corso_frequentato">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                            <option value="aggiungi">Aggiungi</option>
                            <option value="modifica">Modifica</option>
                            <option value="cancella">Cancella</option>
                        </select>
                    </div>

                    <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-6">
                <h3>inserisci o modifica un corso</h3>
                <form class="form-segreteria" action="update_corso.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Id:</span>
                                        <input type="text" class="form-control" name="id" id="id">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Nome:</span>
                                        <input type="text" class="form-control" name="nome" id="nome">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="form-row">
                                        <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                                            <option value="triennale">Triennale</option>
                                            <option value="magistrale">Magistrale</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                            <option value="aggiungi">Aggiungi</option>
                            <option value="modifica">Modifica</option>
                            <option value="cancella">Cancella</option>
                        </select>
                    </div>

                    <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
                </form>
            </div>

            <div class="col-sm-6">
                
                <form class="form-segreteria" action="update_insegnamento.php" method="POST">
                    <h3>inserisci o modifica un insegnamento</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Id:</span>
                                        <input type="text" class="form-control" name="id" id="id">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class = "input-group-text">Nome:</span>
                                        <input type="text" class="form-control" name="nome" id="nome">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="form-row">
                                        <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                                            <option value="primo">Primo</option>
                                            <option value="secondo">Secondo</option>
                                            <option value="terzo">Terzo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                            <option value="aggiungi">Aggiungi</option>
                            <option value="modifica">Modifica</option>
                            <option value="cancella">Cancella</option>
                        </select>
                    </div>

                    <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
                </form>
            </div>
        </div>


        <form action="../change_password.php" style="padding: 5%; justify-content: center; align-items: center; display: flex;" method="POST">
            <h4>vuoi cambiare password? </br></h4>
            <label for="password">password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" style="padding:2%;" class="btn btn-primary">Cambia</button>
        </form>
    </div>


</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>