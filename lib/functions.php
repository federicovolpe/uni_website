<?php
    function display_esami_tablella($matricola) {
        //connessione al database

        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($db){
            $sql = "SELECT ES.esame_id, ES.nome, ES.data
                    FROM studente AS S
                    JOIN
                        (SELECT I.corso AS corso, E.id AS esame_id, I.nome, E.data
                        FROM esami AS E
                        INNER JOIN insegnamento AS I ON I.id = E.insegnamento) AS ES ON ES.corso = S.corso_frequentato
                    WHERE S.matricola = $1";
            $preparato = pg_prepare($db, "esami_iscrivibili", $sql);

            if($preparato){
                $result = pg_execute($db, "esami_iscrivibili", array($matricola));
                if($result){
                    while($row = pg_fetch_assoc($result)){
                        echo("<tr>
                                <td> ". $row['nome']. "</th>
                                <td> voto </th>
                                <th> ". $row['data']. "</th>
                                <th> form iscrizione </th>
                            </tr>");
                    }
                }else{
                    print("l'esecuzione della query non è andata a buon fine</br>");
                }
            }else{
                print("la preparazione della query non è andata a buon fine</br>");
            }
        } else{
            print("connessione con il database fallita");
        }
    }



    function messaggi_errore() {
        echo '<script>
            const urlParams = new URLSearchParams(window.location.search);
            const approved = urlParams.get(\'approved\');
            const msg = urlParams.get(\'msg\');
            if (approved === \'0\') {
                var successMessage = document.createElement(\'div\');
                successMessage.className = \'p-3 mb-2 bg-success text-white\';
                successMessage.textContent = msg;
                document.body.appendChild(successMessage);
            }
            if (approved === \'1\') {
                var successMessage = document.createElement(\'div\');
                successMessage.className = \'p-3 mb-2 bg-danger text-white\';
                successMessage.textContent = msg;
                document.body.appendChild(successMessage);
            }
        </script>';
    }

    function script_boostrap(){
        $import = '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>';
        return $import;
    }

    function verifica_recupera_info(){
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];

        //se le variabili non sono vuote
        if(!empty($email) && !empty($password)){
            $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
            if($conn){
                print("connessione riuscita ricavo le infoo<br>");
                
                if(substr($email, -17) === 'studenti.unimi.it'){
                    print("query per i studenti<br>");
                    $query = "SELECT 1
                        FROM studente
                        WHERE email = $1 AND passwrd = $2 ;";
                    $prepara = pg_prepare($conn, "query_di_verifica", $query);
                    $esito_verifica = pg_execute($conn, "query_di_verifica", array($email, $password));

                    if(pg_num_rows($esito_verifica) >= 1){
                        $query2 = " SELECT *
                            FROM studente
                            WHERE email = $1 AND passwrd = $2 ;";
                        $prepara = pg_prepare($conn, "fetch_info", $query2);
                        $result = pg_execute($conn, "fetch_info", array($email, $password));

                        if($result){  
                            $row = pg_fetch_assoc($result);
                            $_SESSION['nome'] = $row['nome'];
                            $_SESSION['cognome'] = $row['cognome'];    
                            $_SESSION['matricola'] = $row['matricola'];
                            $_SESSION['corso_frequentato'] = $row['corso_frequentato'];
                            
                        }
                    } else {
                        // Accesso non valido, reindirizzamento a pagina di errore
                        print('credenziali non trovate');
                        $url_errore ="../login.php?error=" . urlencode(1);
                        header("Location: " . $url_errore);
                        exit;
                    }
                }
                if(substr($email, -16) === 'docenti.unimi.it'){
                    print("query per i docenti<br>");
                    //query ad hoc per i docenti
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
                            $row = pg_fetch_assoc($result);
                            $_SESSION['nome'] = $row['nome'];
                            $_SESSION['cognome'] = $row['cognome'];    
                            $_SESSION['id'] = $row['id'];
                        }
                    } else {
                        // Accesso non valido, reindirizzamento a pagina di errore
                        print('credenziali non trovate');
                        $url_errore ="../login.php?error=" . urlencode(1);
                        header("Location: " . $url_errore);
                        exit;
                    }
                }
                if(substr($email, -19) === 'segreteria.unimi.it'){
                    //query ad hoc per gli utenti di segreteria
                    print("query per la segreteria<br>");
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
                            $row = pg_fetch_assoc($result);
                            $_SESSION['nome'] = $row['nome'];
                            $_SESSION['cognome'] = $row['cognome'];    
                            $_SESSION['id'] = $row['id'];
                        }
                    } else {
                        // Accesso non valido, reindirizzamento a pagina di errore
                        print('credenziali non trovate');
                        $url_errore ="login.php?error=" . urlencode(1);
                        header("Location: " . $url_errore);
                        exit;
                    }
                }
            }else{  
                print("connessione fallita<br>");
                print("ti riporto al sito precedente<br>");
                $url_errore ="login.php?error=" . urlencode(404);
                header("Location: ". $url_errore);
                exit;
            }
        }
    }
?>