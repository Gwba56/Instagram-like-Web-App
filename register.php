<?php
require_once 'include/header.php';
if(isset($_SESSION['userid'])) {
    $_SESSION['error'] = "Vous êtes déjà connecté";
    header('Location:index.php');
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
    </div>
    <div class="row">
        <div class="col s10 m8 l6 offset-s1 offset-m2 offset-l3">
            <div class="card-panel center-align grey lighten-4">
                <h2 class="header center-align teal-text text-accent-3">Camagru</h2>
                <br/>
                <p>Inscrivez-vous pour créer vos propres photomontages</p>
                
                <form action="controller/user.php?create_user" method="POST">
                    <input type="text" name="username" placeholder="Identifiant" minlength="4" maxlength="21" title="Votre identifiant ne doit comporter que des lettres et des chiffres (min 4 - max 21 caractères)." autofocus required></br>
                    <input type="text" name="email" placeholder="Adresse e-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" maxlength="100" title="Veuillez entrer une adresse mail valide." required></br>
                    <input type="password" name="password" placeholder="Mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" maxlength="50" title="Votre mot de passe doit comporter au moins 8 caractères, dont une lettre majuscule, une lettre minuscule et un chiffre." required></br>
                    <input type="password" name="password_confirmation" placeholder="Confirmez mot de passe" required></br>
                    <br/>
                    <input type="submit" class="btn-large teal accent-4" value="Envoyer">
                </form>
                <br/>
                <br/>
                <div class="">
                    <p>Vous avez un compte ? <a href="login.php">Connectez-vous</a> ou <a href="home.php">Revenir à l'accueil</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'include/footer.php';
?>