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
        </div>
    </nav>
    <div style="text-align: center;"><h3>inserisci o modifica un corso</h3></div>
    
        <form class="form-segreteria" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <select class="form-select" name="operazione" id="operazione" aria-label="Default select example">
                                <!--opzioni fra gli insegnamenti del professore specificato-->
                    <option value="aggiungi">Aggiungi</option>
                    <option value="modifica">Modifica</option>
                    <option value="cancella">Cancella</option>
                </select>
                        </div>
                        <div class="col">
                            <div class="input-group mb-3">
                            <label for="new_date">New Date:</label>
            <input type="date" id="new_date" name="new_date" required>
            <input type="submit" value="Update">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <button type="submit" style="padding:2%;" class="btn btn-primary">Inserisci</button>
        </form>

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>