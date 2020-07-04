<?php
require_once 'include/header.php';
require_once 'include/navbar.php';
if(!isset($_SESSION['userid'])) {
	$_SESSION['error'] = "Vous devez être connecté pour voir cette page";
	header('Location:login.php');
}
if(isset($_SESSION['error'])) {
    include 'include/error.php';
    unset($_SESSION['error']);
} else if(isset($_SESSION['success'])) {
    include 'include/success.php';
    unset($_SESSION['success']);
}
?>
<div class="container">
<div class="row">
<h3 class="header center-align teal-text text-darken-3">Paramètres de votre compte</h3>
</div>
<div class="row">
    <div class="col s12 m6 l6">
        <div class="card-panel grey lighten-4">
        <form action="controller/user.php?modify_user" method="POST">
                        <label>
                            <span>Nom d'utilisateur:</span>
                            <input type="text" name="username" placeholder=<?= $_SESSION["username"] ?> minlength="4" maxlength="21"> 
                        </label>
                        <label>
                            <span>Adresse e-mail:</span>
                            <input type="email" name="email" placeholder=<?= $_SESSION["email"] ?> pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Veuillez entrer une adresse mail valide.">
                        </label>
                        <br/>
                        <br/>
                        <label>
                            <input type="checkbox" id="notifications" name="notifications" <?php if($_SESSION['notifications'] == 1) { echo "checked"; } ?>/>
                            <span>Recevoir des notifications.</span> 
                        </label>
                        <br/>
                        <br/>
                        <br/>
                        <div class="card-action center-align">
                            <button class="btn teal accent-3">Mettre à jour</button>
                        </div>
                        </form>
        </div>
    </div>
    <div class="col s12 m6 l6">
        <div class="card-panel grey lighten-4">
        <form action="controller/user.php?modify_pwd" method="POST">
                        <label>
                        Ancien mot de passe:
                        <input type="password" name="old_password" placeholder="" required/>
                        </label>
                        <label>
                        Nouveau mot de passe:
                        <input type="password" name="password" placeholder="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" maxlength="50" title="Votre mot de passe doit comporter au moins 8 caractères, dont une lettre majuscule, une lettre minuscule et un chiffre." required/>
                        </label>
                        <label>
                        Confirmez le nouveau mot de passe:
                        <input type="password" name="password_confirmation" placeholder="" required/>
                        </label>
                        <br/>
                        <br/>
                        <div class="card-action center-align">
                            <button class="btn teal accent-3">Mettre à jour</button>
                        </div>
                    </form>
        </div>
    </div>
</div>



<?php
require_once 'include/footer.php';
?>