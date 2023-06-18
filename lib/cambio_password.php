<!-- form per la raccolta delle info riguardanti una nuova password utile per tutti gli utenti-->
<div style="display: flex;
            margin-bottom:3%; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center;
            margin: 10%">

    <h4>vuoi cambiare password?</h4>
    <form class="c-password" action="<?php echo $_SERVER['PHP_SELF']; ?>?change_password" method="POST">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" class="btn btn-primary">Cambia</button>
    </form>

</div>
