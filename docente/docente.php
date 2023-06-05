<!--homepage dello studente dove si possono consultare gli esiti degli esami-->
<?php
// Recupero dei dati dal modulo di accesso
    session_start();
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];

if(!empty($email) && !empty($password)){
}
    $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
    if($conn){
        $query = "SELECT 1
                FROM docente
                WHERE email = $1 AND passwrd = $2 ;";
        $prepara = pg_prepare($conn, "query_di_verifica", $query);
        $esito_verifica = pg_execute($conn, "query_di_verifica", array($email, $password));

        if(pg_num_rows($esito_verifica) >= 1){
            $query2 = " SELECT *
                FROM docente
                WHERE email = $1 AND passwrd = $2 ;";
            $prepara = pg_prepare($conn, "fetch_info", $query2);
            $result = pg_execute($conn, "fetch_info", array($email, $password));

            if($result){     
                //se la query riesce a raccogliere dei dati allora li memorizzo
                $row = pg_fetch_assoc($result);
                $nome = $row['nome'];
                $cognome = $row['cognome']; 
                $id = $row['id'];
                //metto la variabile id nella sessione perchè può tornare utile nelle pagine successive
                $_SESSION['id'] = $id;
            }
        } else {
            // Accesso non valido, reindirizzamento a pagina di errore
            print('credenziali non trovate');
            $url_errore ="../login.html?error=" . urlencode(1);
            header("Location: " . $url_errore);
            exit;
        }
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
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../immagini/logo_unimi.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> Università degli Studi di Milano
            </a>
            <button type="button" class="btn btn-outline-primary">logout</button>
        </div>
    </nav>
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
    <div>
        <?php  print("<h1>Benvenuto $nome $cognome</h1>");?>
    </div>
    <div>
        esami programmati dal docente<br>
        <div class= "table-container">
        <table class="table">
            <thead>
                <tr>
                    <th> Insegnamento </th>
                    <th> Data </th>
                    <th> Opzioni </th>
                </tr>
            </thead>
            <?php
                $sql = "SELECT insegnamento.nome as insegnamento_n, data, esami.id as esami_id
                        FROM esami 
                        JOIN insegnamento ON insegnamento.id = esami.insegnamento
                        WHERE docente = $1";
                $prepare = pg_prepare($conn, "esami_in_programma", $sql);
                if ($prepare) {
                    $esami_in_prog = pg_execute($conn, "esami_in_programma", array($id));
                    while ($row = pg_fetch_assoc($esami_in_prog)) {
                        print('
                            <tr>
                                <td>' . $row['insegnamento_n'] . '</td>
                                <td>' . $row['data'] . '</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Azioni">
                                        <button type="button" onclick="redirectToUpdate(\'cancella\', ' . $row['esami_id'] . ')" class="btn btn-outline-primary">Cancella</button>
                                        <button type="button" onclick="redirectToUpdate(\'modifica\', ' . $row['esami_id'] . ')" class="btn btn-outline-primary">Modifica</button>
                                    </div>
                                </td>
                            </tr>
                            <script>
                                function redirectToUpdate(operazione, esame) {
                                    if (operazione === \'cancella\') {
                                        var url = "cancella_esame.php?esame=" + encodeURIComponent(esame);
                                        window.location.href = url;
                                    } else if (operazione === \'modifica\') {
                                        var url = "update_esame.php?esame=" + encodeURIComponent(esame);
                                        window.location.href = url;
                                    }
                                }
                            </script>');
                    }
                }else{
                    print("preparazione della query fallita");
                }
            ?>
        </table>

    </div>
    </form>

    <div style="padding:2%;text-align: center;"> 
        <hr> 
        <h3>programma un nuovo esame:</h3>
    </div>
    <form action="programma_esame.php">
        <label for="insegnamento">Insegnamento</label>
        <select class="form-select" name="insegnamento" id="insegnamento" aria-label="Default select example">
            
        <?php
        //per ogni insegnamento di cui è responsabile il professore creo una opzione
        $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($conn){
            $sql = "SELECT R.docente, I.nome AS nome_insegnamento
                    FROM responsabile_insegnamento AS R
                    JOIN insegnamento AS I ON I.id = R.insegnamento
                    WHERE docente = $1";
            print("query settata</br>");
            $result = pg_prepare($conn, "insegnamenti_responsabile", $sql);
            $insegnamenti = pg_execute($conn, "insegnamenti_responsabile", array($id));
            print("query eseguita</br>");
            if(pg_num_rows($insegnamenti) >= 1){
                print("insegnamenti trovati!");
                
                while($row = pg_fetch_assoc($insegnamenti)){
                    echo("<option value='" . $row['nome_insegnamento'] . "'>".$row['nome_insegnamento']."</option>");
                }
            }else{
                echo("insegnamenti non trovati");
            }
        }else{
            echo("connessione col database marcita");
        }
        ?>
        </select>
    </form>
        
    <div style="margin: 0 auto;text-align: center;">
        <h2>vuoi programmare un nuovo esame </h2>
        <a href="inserzione_esame.php"> programma un nuovo esame </a>
    </div>
    <div style ="padding : 1%">
    <hr>
    </div>
    <div style="display: flex;
        justify-content: center;
        height: 10vh;border:2px solid blue;">
        <div style="display: grid;
        gap: 10px;
        justify-items: center;
        text-align: center;
        ">
            <h3>inserzione esiti</h3>
            <form action="registra_voti.php" method="POST">
                <div style="padding:2%">
                    <label for="m_studente">Matricola studente:</label>
                    <input type="text" name="m_studente" id="m_studente">
                </div>

                <div style="padding:2%">
                    <label for="esame">Esame:</label>
                    <select class="form-select" name="esame" id="esame" aria-label="Default select example">
            
                    <?php 
                        if($esami_in_prog){
                            print("<div>insegnamenti trovatioo</div>". pg_num_rows($esami_in_prog));
                            while($row = pg_fetch_assoc($esami_in_prog)){
                                echo("<option value='" . $row['insegnamento_n'] . "'>" . $row['insegnamento_n'] . "</option>");
                            }
                        }else{
                        print("<div>insegnamenti non trovati</div>");
                        }
                    ?>
                </div>

                <div style="padding:2%">
                    <label for="esito">Esito:</label>
                    <input type="number" name="esito" id="esito" min="0" max="30">
                </div>
                
                <div style="padding:2%">
                    <button type="submit" style="padding:2%;" class="btn btn-primary">Esegui</button>
                </div>
            </form>
        </div>
    </div>

    <form action="../change_password.php" style="padding: 5%; justify-content: center; align-items: center; display: flex;" method="POST">
        <h4>vuoi cambiare password? </br></h4>
        <label for="password">password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" style="padding:2%;" class="btn btn-primary">Cambia</button>
    </form>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>