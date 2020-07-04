<div class="navbar-fixed hide-on-small-only">
<nav>
    <div class="nav-wrapper grey lighten-4">
        <div class="container">
            <div class="row">
                <a href="index.php" class="brand-logo left teal-text text-accent-3">Camagru</a>
                <ul id="nav-mobile" class="right">
                <?php 
                    if(isset($_SESSION['userid']) && $_SESSION['userid'] > 0) { ?>
                        <li><a class="btn-floating teal accent-4" href="home.php"><i class="material-icons">home</i></a></li>
                        <li><a class="btn-floating teal accent-4" href="profile.php"><i class="material-icons">person</i></a></li>
                        <li><a class="btn-floating teal accent-4" href="camera.php"><i class="material-icons">camera_alt</i></a></li>
                        <li><a class="btn-floating teal accent-4" href="account.php"><i class="material-icons">build</i></a></li>
                        <li><a class="btn-floating teal accent-4" href="controller/user.php?logout"><i class="material-icons">exit_to_app</i></a></li>
                    <?php } else { ?> 
                        <li ><a href="login.php"><button class="btn teal accent-4">Se connecter</button></a></li>
                        <li ><a href="register.php"><button class="btn white teal-text text-accent-4">S'inscrire</button></a></li>
                <?php }; ?> 
                </ul>
            </div>
      </div>
    </div>
</nav>
</div>

<div class="navbar-fixed hide-on-med-and-up">
<nav>
    <div class="nav-wrapper grey lighten-4">
        <div class="container left">
            
        <ul class="right">
        <?php 
            if(isset($_SESSION['userid']) && $_SESSION['userid'] > 0) { ?>
                <li ><a class="col s2 offset-s2 btn-floating teal accent-2" href="home.php"><i class="material-icons">home</i></a></li>
                <li ><a class="col s2  btn-floating teal accent-4" href="profile.php"><i class="material-icons">person</i></a></li>
                <li ><a class="col s2  btn-floating teal accent-4" href="camera.php"><i class="material-icons">camera_alt</i></a></li>
                <li ><a class="col s2  btn-floating teal accent-4" href="account.php"><i class="material-icons">build</i></a></li>
                <li ><a class="col s2  btn-floating teal accent-4" href="controller/user.php?logout"><i class="material-icons">exit_to_app</i></a></li>
            <?php } else { ?> 
                <li ><a href="login.php"><button class="btn teal accent-4">Se connecter</button></a></li>
                <li ><a href="register.php"><button class="btn white teal-text text-accent-4">S'inscrire</button></a></li>
        <?php }; ?> 
        </ul>
            
            
      </div>
    </div>
</nav>
</div>
