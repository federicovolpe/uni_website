<?php
    //include functions.php
    session_start();
    include_once("lib/functions.php");
    
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        body {
            background-color: black;
        }
    </style>
</head>

<body>
        <?php
            //include del file navbar.php
            include("../lib/navbar.php");
        ?>
    <div class="login-form">
        <div style="display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;">
            <h1 class="text-center" style="color: blue">Benvenuto nella pagina di login</h1>

            <form style="width: 50%; justify-content:center; align-items: center;"action="dispatcher.php" method="POST">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="email">Email</span>
                    <input type="text" class="form-control"  name="email" placeholder="Inserisci email" aria-label="email" aria-describedby="basic-addon1">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="codice_accesso">Password</span>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Inserisci password" aria-label="Password" aria-describedby="basic-addon1">

                </div>
                <select class="form-select" name="tipologia" id="tipologia" aria-label="Default select example">
                        <option value="studente">Studente</option>
                        <option value="docente">Docente</option>
                        <option value="segreteria">Segreteria</option>
                </select>
                <div>
                    <script>
                        var params = new URLSearchParams(window.location.search);
                        var error = params.get('error');
                      
                        if (error === '404') {
                            var errorMessage = document.createElement('div');
                            errorMessage.className = 'alert alert-danger';
                            errorMessage.textContent = 'connessione al database fallita!';
                            document.body.appendChild(errorMessage);
                        }
                        if (error === '1'){
                            var errorMessage = document.createElement('div');
                            errorMessage.className = 'alert alert-danger';
                            errorMessage.textContent = 'le credenziali risultano errate!';
                            document.body.appendChild(errorMessage);
                        }
                      </script>
                      
                    <input type="submit" id="accedi" label="Accedi" style="float: right" value="Accedi">
                </div>
            </form>
        </div>
    </div>

    <!-- contenitore di fine pagina -->
    <footer class="footer">
        <div class="footer-content" style="display: flex; 
            align-items: center; 
            justify-content: center; 
            flex-direction: column; 
            text-align: center;">
            <h3>Intestazione</h3>
            sito creato da Federico Volpe per il progetto del corso di basi di dati, esame del 15/6/2023
            <ul>
                <li class="contenuti-footer">
                    <a href="https://github.com/federicovolpe/uni_website">repository github</a>
                </li>
                <li class="contenuti-footer">
                    <a href="https://cas.unimi.it/login?service=https%3A%2F%2Funimia.unimi.it%2Fportal%2Fserver.pt%2Fcommunity%2Funimia%2F207">sito ufficiale unimi</a>
                </li>
                <li class="contenuti-footer">
                    <a href="esempiolink">contatti</a>
                </li>
            </ul>
        </div>
    </footer>

</body>
<?php script_boostrap()?>
</html>