<?php
include 'include/header.php';
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
                <form action="controller/user.php?activate_account" method="POST">
                    <input type="hidden" name="token" value="<?= $_GET['activate_account'] ?>">
                    <input type="text" name="username" placeholder="Identifiant" autofocus required/></br>
                    <input type="password" name="password" placeholder="Mot de passe" required/></br>
                    <br/>
                    <button class="btn-large teal accent-4">Activation</button>
                </form>
                <br/>
                <p><a href="resetPsswd.php">Mot de passe oublié ?</a></p>
                <div class="">
                    <p>Vous n'avez pas de compte ? <a href="register.php">Inscrivez vous</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'include/footer.php';
?>