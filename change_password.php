<?php
session_start();
    //unico file per il cambio password di docente e segreteria, cambia db in base alla tipologia di utente
    if (session_status() == PHP_SESSION_ACTIVE) {
        print("fancoolo </br>");
    $id = $_SESSION['id'];
    $matricola = $_SESSION['matricola'];
    print("id: ".$id . "</br>");
    $tipologia = $_SESSION['tipologia'];
    print("tipologia utente: ". $tipologia . "</br>");
    $nuova_password = $_POST['password'];
    print("nuova password: ".$nuova_password . "</br>");
        //stabilisci connessione con il database
        $db = pg_connect("dbname = unimio host = localhost port = 5432");
        if($db){
                if($tipologia === 'segreteria'){
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

                }else if($tipologia ==='docente'){
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
                }else if($tipologia ==='studente'){
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