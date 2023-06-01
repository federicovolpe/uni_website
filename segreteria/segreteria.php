<?php
// Recupero dei dati dal modulo di accesso
    session_start();
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
if(!empty($email) && !empty($password)){
    
    /*print("tentato l'accesso con le credenziali: <br>");
    print("email: $email -</br>");
    print("password: $password -</br>");*/
}
    $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
    if($conn){
        $query = "SELECT 1
                FROM segreteria
                WHERE email = $1 AND passwrd = $2 ;";
        $prepara = pg_prepare($conn, "query_di_verifica", $query);
        $esito_verifica = pg_execute($conn, "query_di_verifica", array($email, $password));

        if(pg_num_rows($esito_verifica) >= 1){
            $query2 = " SELECT *
                FROM segreteria
                WHERE email = $1 AND passwrd = $2 ;";
            $prepara = pg_prepare($conn, "fetch_info", $query2);
            $result = pg_execute($conn, "fetch_info", array($email, $password));

            if($result){     
                //se la query riesce a raccogliere dei dati allora li memorizzo
                $row = pg_fetch_assoc($result);
                $nome = $row['nome'];
                $cognome = $row['cognome']; 

                //print("nome: $nome</br>");
                //print("cognome: $cognome</br>");
            }
        } else {
            // Accesso non valido, reindirizzamento a pagina di errore
            print('credenziali non trovate');
            $url_errore ="../login.html?error=" . urlencode(1);
            header("Location: " . $url_errore);
            exit;
        }
        // Chiusura della connessione al database
        pg_close($conn);
    }else{  
        print("connessione fallita<br>");
        print("ti riporto al sito precedente<br>");
        $url_errore ="../login.html?error=" . urlencode(404);
        header("Location: ". $url_errore);
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
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../immagini/logo_unimi.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> Università degli Studi di Milano
            </a>
        </div>
    </nav>
        <?php  print("<h1>Benvenuto $nome $cognome</h1>");?>
    <div>

        <div><!-- divisione per i messaggi di errore o di successo-->
            <script>
                if (approved === '0') {
                            var successMessage = document.createElement('div');
                            successMessage.className = 'alert alert-success';
                            successMessage.textContent = 'operazione approvata dal database';
                            document.body.appendChild(successMessage);
                }
                if (approved === '1') {
                            var successMessage = document.createElement('div');
                            successMessage.className = 'alert alert-danger';
                            successMessage.textContent = 'operazione non andata a buon fine';
                            document.body.appendChild(successMessage);
                }
            </script>
        </div>

        <h3> questa è la homepage della segreteria</h3>
        <div class="row">
            <div class="col-sm-6">
                <h3>modifica o aggiungi un docente</h3>
                <form class="form-segreteria" action="update_docente.php" method="POST">
                    <label for="id">id:</label>
                    <input type="text" name="id" id="id" required>

                    <label for="nome">nome:</label>
                    <input type="text" name="nome" id="nome" required>
                    
                    <label for="cognome">cognome:</label>
                    <input type="text" name="cognome" id="cognome" required>

                    <label for="email">email:</label>
                    <input type="text" name="email" id="email" required>

                    <label for="password">password:</label>
                    <input type="text" name="password" id="password" required>

                    <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                                    <option value="aggiungi">Aggiungi</option>
                                    <option value="modifica">Modifica</option>
                                    <option value="cancella">Cancella</option>
                    </select>
                    
                    <button type="submit">Esegui</button>
                </form>
            </div>

            <div class="col-sm-6">
                <h3>Modifica o aggiungi uno studente</h3>
                <form class="form-segreteria" action="update_studente.php" method="POST">
                        
                    <label for="matricola">Matricola:</label>
                    <input type="text" name="matricola" id="matricola" required>

                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" id="nome">
                    
                    <label for="cognome">Cognome:</label>
                    <input type="text" name="cognome" id="cognome" >

                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email" >

                    <label for="password">Password:</label>
                    <input type="text" name="password" id="password">

                    <label for="corso_frequentato">Corso frequentato:</label>
                    <input type="text" name="corso_frequentato" id="corso_frequentato">

                    <label for="operazione">Operazione:</label>
                    <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                                    <option value="aggiungi">Aggiungi</option>
                                    <option value="modifica">Modifica</option>
                                    <option value="cancella">Cancella</option>
                    </select>
                    
                    <button type="submit">Esegui</button>
                </form>
        </div>

        <div class="row mt-4">
            <div class="col-sm-6">
                <h3>inserisci o modifica un corso</h3>
                <form class="form-segreteria" action="update_corso.php" method="POST">
                        
                    <label for="id">id:</label>
                    <input type="text" name="id" id="id" required>

                    <label for="nome">nome:</label>
                    <input type="text" name="nome" id="nome" required>
                    
                    <select class="form-select" name="tipologia" id="tipologia" aria-label="tipologia">
                                    <option value="triennale">triennale</option>
                                    <option value="magistrale">magistrale</option>
                    </select>

                    <select class="form-select" name="operazione" id="operazione" aria-label="operazione">
                                    <option value="aggiungi">Aggiungi</option>
                                    <option value="modifica">Modifica</option>
                                    <option value="cancella">Cancella</option>
                    </select>
                    
                    <button type="submit">Esegui</button>
                </form>
            </div>

            <div class="col-sm-6">
                <h3>inserisci o modifica un insegnamento</h3>
                <form class="form-segreteria" action="update_insegnamento.php" method="POST">
                        
                    <label for="id">id:</label>
                    <input type="text" name="id" id="id" required>

                    <label for="nome">nome:</label>
                    <input type="text" name="nome" id="nome" required>
                    
                    <select class="form-select" name="anno" id="anno" aria-label="anno">
                                    <option value="primo">primo</option>
                                    <option value="secondo">secondo</option>
                                    <option value="terzo">terzo</option>
                    </select>

                    <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                                    <option value="aggiungi">Aggiungi</option>
                                    <option value="modifica">Modifica</option>
                                    <option value="cancella">Cancella</option>
                    </select>
                    
                    <button type="submit">Esegui</button>
                </form>
            </div>
        </div>

        
        <form action="change_password.php" style="padding: 5%; justify-content: center; align-items: center; display: flex;">
            <h4>vuoi cambiare password?    </br></h4>
            <label for="password">password:</label>
            <input type="text" name="password" id="password" required>
        </form>

    
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>