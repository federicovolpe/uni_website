<?php
    //include delle funzioni
    include("../lib/functions.php");
    session_start();
    
    //se il parametro in get update_docente sono settati
    if(isset($_GET['update_docente'])){
        //chiamo la funzione update_docente
        include_once('update_docente.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php
    include_once("../lib/head.php"); 
    include_once('../lib/navbar.php');
?>
<body>
    <nav class="navbar bg-body-tertiary">
        <?php include_once('navbar.php')?>
    </nav>
    <?php //stampa di eventuali messaggi di errore
        messaggi_errore_post2();
        messaggi_errore();
    ?>

        <div class="row">
            <div class="col-sm-6">
                <h3>modifica o aggiungi un docente</h3>
                <form class="form-segreteria" action="<?php echo $_SERVER['PHP_SELF']; ?>?update_docente" method="POST">
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


        <?php include_once('../lib/cambio_password.php')?>
    </div>


</body>
    <?php script_boostrap()?>
</html>