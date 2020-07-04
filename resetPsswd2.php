<?php
require_once 'include/header.php';
if(isset($_SESSION['userid'])) {
    $_SESSION['error'] = "Vous êtes déjà connecté";
    header('Location:home.php');
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
                <h5>Changement de mot de passe</h5>
                <p>Entrez votre nom d’utilisateur afin que nous vérifiions votre identité</p>
                <br/>
                <form action="controller/user.php?reset_pwd" method="POST">
                    <input type="hidden" name="token" value="<?= $_GET['reset_pwd'] ?>">
                    <input type="text" name="username" placeholder="Nom d'utilisateur" autofocus required/></br>
                    <input type="password" name="password" placeholder="Nouveau mot de passe" required/></br>
                    <input type="password" name="password_confirmation" placeholder="Confirmez mot de passe" required/></br>
                    <br/>
                    <button class="btn-large teal accent-4">Envoyer</button>
                </form>
                <br/>
                <br/>
                <div class="">
                <p><a href="register.php">Créer un compte</a> ou <a href="index.php">Revenir à l'accueil</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'include/footer.php';
?>