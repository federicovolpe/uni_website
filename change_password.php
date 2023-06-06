<?php
session_start();
    //unico file per il cambio password di docente e segreteria, cambia db in base alla tipologia di utente
    if (session_status() == PHP_SESSION_ACTIVE) {
    $id = $_SESSION['id'];
    $matricola = $_SESSION['matricola'];
    $email = $_SESSION['email'];
    $nuova_password = $_POST['password'];


    print("nuova password: ".$nuova_password . "</br>per l'utente: " . $id . "".$matricola."</br>con mail: ".$email."</br>");
        //stabilisci connessione con il database
        $db = pg_connect("dbname = unimio host = localhost port = 5432");
        if($db){
            print("controllo: ". $email."</br>");
            if(substr($email, -19) === 'segreteria.unimi.it'){
                $sql = "UPDATE segreteria
                        SET passwrd = $1
                        WHERE id = $2";
                $preparato = pg_prepare($db, "cambio_password", $sql);
                if($preparato){
                    $result = pg_execute($db, "cambio_password", array($nuova_password, $id));
                    if($result){
                        print("la password di: $id, è stata cambiata in $nuova_password");

                    }else{
                        print("l'esecuzione della query non è andata a buon fine");
                    }
                }else{
                    print("preparazione della query non riuscita");
                }

            }else if(substr($email, -16) === 'docenti.unimi.it'){
                $sql = "UPDATE docente
                        SET passwrd = $1
                        WHERE id = $2";
                $preparato = pg_prepare($db, "cambio_password", $sql);
                if($preparato){
                    $result = pg_execute($db, "cambio_password", array($nuova_password, $id));
                    if($result){
                        print("la password di: $id, è stata cambiata in $nuova_password");

                    }else{
                        print("l'esecuzione della query non è andata a buon fine");
                    }
                }else{
                    print("preparazione della query non riuscita");
                }
            }else if(substr($email, -17) === 'studenti.unimi.it'){
                print("cambio password per studente </br>");
                $sql = "UPDATE studente
                        SET passwrd = $1
                        WHERE matricola = $2";
                $preparato = pg_prepare($db, "cambio_password", $sql);
                if($preparato){
                    $result = pg_execute($db, "cambio_password", array($nuova_password, $matricola));
                    if($result){
                        print("la password di: $matricola, è stata cambiata in $nuova_password");

                    }else{
                        print("l'esecuzione della query non è andata a buon fine");
                    }
                }else{
                    print("preparazione della query non riuscita");
                }
            }   
        }else{
            print("connessione al db non riuscita");
        }
        
    }else{
          print("la sessione non risulta attiva");
    }

?>