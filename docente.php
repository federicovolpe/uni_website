<!--homepage dello studente dove si possono consultare gli esiti degli esami-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
    <div>
        <h1>Benvenuto %docente_nome %studente_cognome</h1>
    </div>
    <div>
        questa è la homepage del docente<br>
        <div class= "table-container">
        <table class="table">
            <thead>
                <tr>
                    <th> Materia </th>
                    <th> Voto </th>
                    <th> Data </th>
                </tr>
            </thead>
            <tr>
                <td> %materia1 </td>
                <td> %voto </td>
                <td> %data </td>
            </tr>
            <tr>
                <td> %materia2 </td>
                <td> %voto </td>
                <td> %data </td>
            </tr>
            <tr>
                <td> %materia3 </td>
                <td> %voto </td>
                <td> %data </td>
            </tr>
        </table>
    </div>
        
    <div>
        <h2>vuoi programmare un nuovo esame </h2>
        <a href="inserzione esame"> programma un nuovo esame </a>
    </div>
    <footer>
        <a href="index.php">homepage</a>
    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</html>